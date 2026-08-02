<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ConsentStatus;
use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\MediaAsset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('galleries.view'), 403);

        $galleries = Gallery::query()->withCount('items')->latest()->paginate(20);

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->can('galleries.create'), 403);

        return view('admin.galleries.edit', [
            'gallery' => new Gallery(['status' => PublishStatus::Draft]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->can('galleries.create'), 403);

        $gallery = Gallery::create($this->validateGallery($request));

        return redirect()->route('admin.galleries.edit', $gallery)->with('success', 'Gallery created. You can now drop images below.');
    }

    public function edit(Gallery $gallery): View
    {
        abort_unless(auth()->user()?->can('galleries.update'), 403);

        $gallery->load(['items.mediaAsset']);

        return view('admin.galleries.edit', [
            'gallery' => $gallery,
        ]);
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        abort_unless(auth()->user()?->can('galleries.update'), 403);

        $gallery->update($this->validateGallery($request));

        return back()->with('success', 'Gallery updated.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        abort_unless(auth()->user()?->can('galleries.delete'), 403);

        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery deleted.');
    }

    public function publish(Gallery $gallery): RedirectResponse
    {
        abort_unless(auth()->user()?->can('galleries.publish'), 403);

        $gallery->publish();

        return back()->with('success', 'Gallery published.');
    }

    public function unpublish(Gallery $gallery): RedirectResponse
    {
        abort_unless(auth()->user()?->can('galleries.publish'), 403);

        $gallery->unpublish();

        return back()->with('success', 'Gallery unpublished.');
    }

    public function upload(Request $request, Gallery $gallery): JsonResponse
    {
        abort_unless(auth()->user()?->can('galleries.update'), 403);
        abort_unless(auth()->user()?->can('media.upload'), 403);

        $validated = $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:30'],
            'files.*' => ['required', 'file', 'image', 'max:10240'],
        ]);

        $created = [];
        $maxSort = (int) ($gallery->items()->max('sort_order') ?? 0);

        foreach (array_values($validated['files']) as $index => $file) {
            $path = $file->store('galleries', 'public');

            $asset = MediaAsset::create([
                'disk' => 'public',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType() ?? 'image/jpeg',
                'size' => $file->getSize(),
                'alt' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'folder' => 'galleries',
                'consent_status' => ConsentStatus::NotRequired,
                'is_private' => false,
            ]);

            $item = $gallery->items()->create([
                'media_asset_id' => $asset->id,
                'caption' => null,
                'sort_order' => $maxSort + $index + 1,
            ]);

            $created[] = [
                'id' => $item->id,
                'media_asset_id' => $asset->id,
                'caption' => $item->caption,
                'sort_order' => $item->sort_order,
                'url' => $asset->publicUrl(),
                'alt' => $asset->alt ?: $asset->filename,
            ];
        }

        return response()->json([
            'message' => count($created) === 1 ? '1 image uploaded.' : count($created).' images uploaded.',
            'items' => $created,
        ]);
    }

    public function updateItem(Request $request, Gallery $gallery, GalleryItem $item): JsonResponse
    {
        abort_unless(auth()->user()?->can('galleries.update'), 403);
        abort_unless($item->gallery_id === $gallery->id, 404);

        $data = $request->validate([
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        $item->update([
            'caption' => filled($data['caption'] ?? null) ? trim((string) $data['caption']) : null,
        ]);

        return response()->json([
            'message' => 'Caption saved.',
            'item' => [
                'id' => $item->id,
                'caption' => $item->caption,
            ],
        ]);
    }

    public function destroyItem(Gallery $gallery, GalleryItem $item): JsonResponse
    {
        abort_unless(auth()->user()?->can('galleries.update'), 403);
        abort_unless($item->gallery_id === $gallery->id, 404);

        $item->delete();

        return response()->json(['message' => 'Image removed from gallery.']);
    }

    private function validateGallery(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'gallery_date' => ['nullable', 'date'],
            'requires_safeguarding' => ['nullable', 'boolean'],
        ]);

        $data['requires_safeguarding'] = $request->boolean('requires_safeguarding');

        return $data;
    }
}
