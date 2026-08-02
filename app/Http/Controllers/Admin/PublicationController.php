<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Admin\Concerns\NormalizesNullableIds;
use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicationController extends Controller
{
    use NormalizesNullableIds;

    public function index(): View
    {
        abort_unless(auth()->user()?->can('publications.view'), 403);

        $publications = Publication::query()->latest()->paginate(20);

        return view('admin.publications.index', compact('publications'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('publications.create'), 403);

        return view('admin.publications.edit', ['publication' => new Publication(['status' => PublishStatus::Draft, 'category' => 'other'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('publications.create'), 403);

        $this->nullEmptyIds($request, ['cover_id', 'file_id', 'accessible_file_id']);

        $publication = Publication::create($this->validatePublication($request));

        return redirect()->route('admin.publications.edit', $publication)->with('success', 'Publication created.');
    }

    public function edit(Publication $publication): View
    {
        abort_unless(auth()->user()?->can('publications.update'), 403);

        return view('admin.publications.edit', compact('publication'));
    }

    public function update(Request $request, Publication $publication): RedirectResponse
    {
        abort_unless(auth()->user()?->can('publications.update'), 403);

        $this->nullEmptyIds($request, ['cover_id', 'file_id', 'accessible_file_id']);

        $publication->update($this->validatePublication($request, $publication));

        return back()->with('success', 'Publication updated.');
    }

    public function destroy(Publication $publication): RedirectResponse
    {
        abort_unless(auth()->user()?->can('publications.delete'), 403);

        $publication->delete();

        return redirect()->route('admin.publications.index')->with('success', 'Publication deleted.');
    }

    public function publish(Publication $publication): RedirectResponse
    {
        abort_unless(auth()->user()?->can('publications.publish'), 403);

        $publication->publish();

        return back()->with('success', 'Publication published.');
    }

    public function unpublish(Publication $publication): RedirectResponse
    {
        abort_unless(auth()->user()?->can('publications.publish'), 403);

        $publication->unpublish();

        return back()->with('success', 'Publication unpublished.');
    }

    private function validatePublication(Request $request, ?Publication $publication = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:publications,slug,'.($publication?->id ?? 'NULL')],
            'category' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'abstract' => ['nullable', 'string'],
            'authors' => ['nullable', 'string'],
            'version' => ['nullable', 'string', 'max:50'],
            'cover_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'file_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'accessible_file_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'verification_status' => ['nullable', 'string'],
        ]);
    }
}
