<?php

namespace App\Http\Controllers\Admin;

use App\Enums\FormSubmissionStatus;
use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FormSubmissionController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('form_submissions.view'), 403);

        $submissions = FormSubmission::query()
            ->with('formDefinition')
            ->latest()
            ->paginate(25);

        return view('admin.form-submissions.index', compact('submissions'));
    }

    public function show(FormSubmission $formSubmission): View
    {
        abort_unless(auth()->user()?->can('form_submissions.view'), 403);

        $formSubmission->load('formDefinition', 'assignedTo');
        $assignees = \App\Models\User::query()->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.form-submissions.show', compact('formSubmission', 'assignees'));
    }

    public function update(Request $request, FormSubmission $formSubmission): RedirectResponse
    {
        abort_unless(auth()->user()?->can('form_submissions.update'), 403);

        if ($request->input('assigned_to') === '') {
            $request->merge(['assigned_to' => null]);
        }

        $validated = $request->validate([
            'status' => ['required', 'string'],
            'admin_notes' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $formSubmission->update($validated);

        return back()->with('success', 'Submission updated.');
    }

    public function export(): StreamedResponse
    {
        abort_unless(auth()->user()?->can('form_submissions.export'), 403);

        $filename = 'form-submissions-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Form', 'Status', 'Submitted At', 'Data']);

            FormSubmission::query()
                ->with('formDefinition')
                ->orderBy('id')
                ->chunk(100, function ($submissions) use ($handle) {
                    foreach ($submissions as $submission) {
                        fputcsv($handle, [
                            $submission->id,
                            $submission->formDefinition?->name,
                            $submission->status instanceof FormSubmissionStatus
                                ? $submission->status->value
                                : $submission->status,
                            $submission->created_at?->toDateTimeString(),
                            json_encode($submission->data),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
