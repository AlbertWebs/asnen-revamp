<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Admin\Concerns\NormalizesNullableIds;
use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramController extends Controller
{
    use NormalizesNullableIds;

    public function index(): View
    {
        abort_unless(auth()->user()?->can('programs.view'), 403);

        $programs = Program::query()->latest()->paginate(20);

        return view('admin.programs.index', compact('programs'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('programs.create'), 403);

        return view('admin.programs.edit', ['program' => new Program(['status' => PublishStatus::Draft])]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('programs.create'), 403);

        $this->nullEmptyIds($request, ['featured_image_id']);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:programs,slug'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'featured_image_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'sort_order' => ['nullable', 'integer'],
            'verification_status' => ['nullable', 'string'],
        ]);

        $program = Program::create($validated);

        return redirect()->route('admin.programs.edit', $program)->with('success', 'Program created.');
    }

    public function edit(Program $program): View
    {
        abort_unless(auth()->user()?->can('programs.update'), 403);

        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, Program $program): RedirectResponse
    {
        abort_unless(auth()->user()?->can('programs.update'), 403);

        $this->nullEmptyIds($request, ['featured_image_id']);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:programs,slug,'.$program->id],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'featured_image_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'sort_order' => ['nullable', 'integer'],
            'verification_status' => ['nullable', 'string'],
        ]);

        $program->update($validated);

        return back()->with('success', 'Program updated.');
    }

    public function destroy(Program $program): RedirectResponse
    {
        abort_unless(auth()->user()?->can('programs.delete'), 403);

        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Program deleted.');
    }

    public function publish(Program $program): RedirectResponse
    {
        abort_unless(auth()->user()?->can('programs.publish'), 403);

        $program->publish();

        return back()->with('success', 'Program published.');
    }

    public function unpublish(Program $program): RedirectResponse
    {
        abort_unless(auth()->user()?->can('programs.publish'), 403);

        $program->unpublish();

        return back()->with('success', 'Program unpublished.');
    }
}
