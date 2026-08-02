<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ConsentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaUploadRequest;
use App\Models\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaAssetController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', MediaAsset::class);

        $assets = MediaAsset::query()->latest()->paginate(24);

        return view('admin.media.index', compact('assets'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', MediaAsset::class);

        return view('admin.media.create', [
            'returnUrl' => $request->query('return'),
            'defaultFolder' => $request->query('folder', 'uploads'),
        ]);
    }

    public function store(MediaUploadRequest $request): RedirectResponse
    {
        $file = $request->file('file');
        $folder = $request->input('folder', 'uploads');
        $path = $file->store($folder, 'public');

        $asset = MediaAsset::create([
            'disk' => 'public',
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType() ?? 'application/octet-stream',
            'size' => $file->getSize(),
            'alt' => $request->input('alt'),
            'caption' => $request->input('caption'),
            'folder' => $folder,
            'consent_status' => $request->input('consent_status', ConsentStatus::Pending->value),
        ]);

        $return = $request->input('return');
        if (is_string($return) && str_starts_with($return, url('/'))) {
            return redirect($return)
                ->with('success', 'Media uploaded. Select it in the image field and save.');
        }

        return redirect()
            ->route('admin.media.edit', $asset)
            ->with('success', 'Media uploaded successfully. You can now attach it on content edit screens.');
    }

    public function edit(MediaAsset $medium): View
    {
        $this->authorize('update', $medium);

        return view('admin.media.edit', ['asset' => $medium]);
    }

    public function update(Request $request, MediaAsset $medium): RedirectResponse
    {
        $this->authorize('update', $medium);

        $validated = $request->validate([
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
            'credit' => ['nullable', 'string', 'max:255'],
            'copyright' => ['nullable', 'string', 'max:255'],
            'folder' => ['nullable', 'string', 'max:100'],
            'consent_status' => ['nullable', 'string'],
            'consent_notes' => ['nullable', 'string'],
            'is_private' => ['nullable', 'boolean'],
        ]);

        $medium->update($validated);

        return back()->with('success', 'Media metadata updated.');
    }

    public function destroy(MediaAsset $medium): RedirectResponse
    {
        $this->authorize('delete', $medium);

        if ($medium->path && Storage::disk($medium->disk)->exists($medium->path)) {
            Storage::disk($medium->disk)->delete($medium->path);
        }

        $medium->delete();

        return redirect()->route('admin.media.index')->with('success', 'Media deleted.');
    }
}
