<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Admin\Concerns\NormalizesNullableIds;
use App\Http\Controllers\Controller;
use App\Models\Webinar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebinarController extends Controller
{
    use NormalizesNullableIds;

    public function index(): View
    {
        abort_unless(auth()->user()?->can('webinars.view'), 403);

        $webinars = Webinar::query()->latest()->paginate(20);

        return view('admin.webinars.index', compact('webinars'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('webinars.create'), 403);

        return view('admin.webinars.edit', ['webinar' => new Webinar(['status' => PublishStatus::Draft])]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('webinars.create'), 403);

        $webinar = Webinar::create($this->validateWebinar($request));

        return redirect()->route('admin.webinars.edit', $webinar)->with('success', 'Webinar created.');
    }

    public function edit(Webinar $webinar): View
    {
        abort_unless(auth()->user()?->can('webinars.update'), 403);

        return view('admin.webinars.edit', compact('webinar'));
    }

    public function update(Request $request, Webinar $webinar): RedirectResponse
    {
        abort_unless(auth()->user()?->can('webinars.update'), 403);

        $webinar->update($this->validateWebinar($request, $webinar));

        return back()->with('success', 'Webinar updated.');
    }

    public function destroy(Webinar $webinar): RedirectResponse
    {
        abort_unless(auth()->user()?->can('webinars.delete'), 403);

        $webinar->delete();

        return redirect()->route('admin.webinars.index')->with('success', 'Webinar deleted.');
    }

    public function publish(Webinar $webinar): RedirectResponse
    {
        abort_unless(auth()->user()?->can('webinars.publish'), 403);

        $webinar->publish();

        return back()->with('success', 'Webinar published.');
    }

    public function unpublish(Webinar $webinar): RedirectResponse
    {
        abort_unless(auth()->user()?->can('webinars.publish'), 403);

        $webinar->unpublish();

        return back()->with('success', 'Webinar unpublished.');
    }

    private function validateWebinar(Request $request, ?Webinar $webinar = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:webinars,slug,'.($webinar?->id ?? 'NULL')],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'held_at' => ['nullable', 'date'],
            'moderator' => ['nullable', 'string', 'max:255'],
            'participant_count' => ['nullable', 'integer', 'min:0'],
            'recording_url' => ['nullable', 'url', 'max:500'],
            'transcript' => ['nullable', 'string'],
            'featured_image_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'verification_status' => ['nullable', 'string'],
        ]);
    }
}
