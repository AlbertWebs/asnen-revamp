<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('announcements.view'), 403);

        $announcements = Announcement::query()->latest()->paginate(20);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('announcements.create'), 403);

        return view('admin.announcements.edit', ['announcement' => new Announcement(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('announcements.create'), 403);

        $announcement = Announcement::create($this->validateAnnouncement($request));

        return redirect()->route('admin.announcements.edit', $announcement)->with('success', 'Announcement created.');
    }

    public function edit(Announcement $announcement): View
    {
        abort_unless(auth()->user()?->can('announcements.update'), 403);

        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        abort_unless(auth()->user()?->can('announcements.update'), 403);

        $announcement->update($this->validateAnnouncement($request));

        return back()->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        abort_unless(auth()->user()?->can('announcements.delete'), 403);

        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted.');
    }

    private function validateAnnouncement(Request $request): array
    {
        return $request->validate([
            'message' => ['required', 'string'],
            'link_url' => ['nullable', 'url', 'max:500'],
            'link_label' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
        ]);
    }
}
