<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ConsentStatus;
use App\Enums\PublishStatus;
use App\Enums\SafeguardingStatus;
use App\Enums\VerificationStatus;
use App\Http\Controllers\Admin\Concerns\NormalizesNullableIds;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\ImpactStory;
use App\Models\MediaAsset;
use App\Models\Partner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ImpactStoryController extends Controller
{
    use NormalizesNullableIds;

    public function index(): View
    {
        $this->authorize('viewAny', ImpactStory::class);

        $stories = ImpactStory::query()->latest()->paginate(20);

        return view('admin.impact-stories.index', compact('stories'));
    }

    public function create(): View
    {
        $this->authorize('create', ImpactStory::class);

        return view('admin.impact-stories.edit', [
            'story' => new ImpactStory([
                'status' => PublishStatus::Draft,
                'requires_safeguarding' => true,
                'safeguarding_status' => SafeguardingStatus::Pending,
            ]),
            'gallery' => null,
            'availablePartners' => collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', ImpactStory::class);

        $this->nullEmptyIds($request, ['featured_image_id']);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:impact_stories,slug'],
            'summary' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'story_date' => ['nullable', 'date'],
            'featured_image_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'requires_safeguarding' => ['nullable', 'boolean'],
            'challenges' => ['nullable', 'string'],
            'learnings' => ['nullable', 'string'],
            'next_steps' => ['nullable', 'string'],
            'verification_status' => ['nullable', 'string'],
        ]);

        $story = ImpactStory::create($validated);
        $this->ensureGallery($story);

        return redirect()->route('admin.impact-stories.edit', $story)->with('success', 'Impact story created. You can now drop gallery images below.');
    }

    public function edit(ImpactStory $impactStory): View
    {
        $this->authorize('update', $impactStory);

        $gallery = $this->ensureGallery($impactStory);
        $impactStory->load(['partners.logo']);

        return view('admin.impact-stories.edit', [
            'story' => $impactStory,
            'gallery' => $gallery,
            'availablePartners' => Partner::query()
                ->with('logo')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, ImpactStory $impactStory): RedirectResponse
    {
        $this->authorize('update', $impactStory);

        $this->nullEmptyIds($request, ['featured_image_id']);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:impact_stories,slug,'.$impactStory->id],
            'summary' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'story_date' => ['nullable', 'date'],
            'featured_image_id' => ['nullable', 'integer', 'exists:media_assets,id'],
            'requires_safeguarding' => ['nullable', 'boolean'],
            'challenges' => ['nullable', 'string'],
            'learnings' => ['nullable', 'string'],
            'next_steps' => ['nullable', 'string'],
            'verification_status' => ['nullable', 'string'],
        ]);

        $impactStory->update($validated);
        $this->ensureGallery($impactStory);

        return back()->with('success', 'Impact story updated.');
    }

    public function destroy(ImpactStory $impactStory): RedirectResponse
    {
        $this->authorize('delete', $impactStory);

        $impactStory->delete();

        return redirect()->route('admin.impact-stories.index')->with('success', 'Impact story deleted.');
    }

    public function publish(ImpactStory $impactStory): RedirectResponse
    {
        $this->authorize('publish', $impactStory);

        if (! $impactStory->canPublishSafely()) {
            return back()->with('error', 'Safeguarding approval is required before publishing.');
        }

        $impactStory->publish();

        return back()->with('success', 'Impact story published.');
    }

    public function unpublish(ImpactStory $impactStory): RedirectResponse
    {
        $this->authorize('publish', $impactStory);

        $impactStory->unpublish();

        return back()->with('success', 'Impact story unpublished.');
    }

    public function approveSafeguarding(ImpactStory $impactStory): RedirectResponse
    {
        $this->authorize('approveSafeguarding', $impactStory);

        $impactStory->update(['safeguarding_status' => SafeguardingStatus::Approved]);

        return back()->with('success', 'Safeguarding approved.');
    }

    public function uploadPartners(Request $request, ImpactStory $impactStory): JsonResponse
    {
        $this->authorize('update', $impactStory);
        abort_unless(auth()->user()?->can('media.upload'), 403);

        $validated = $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:20'],
            'files.*' => ['required', 'file', 'mimes:jpeg,jpg,png,gif,webp,svg', 'max:5120'],
            'names' => ['nullable', 'array'],
            'names.*' => ['nullable', 'string', 'max:255'],
        ]);

        $created = [];
        $maxSort = (int) (Partner::query()->max('sort_order') ?? 0);

        foreach (array_values($validated['files']) as $index => $file) {
            $path = $file->store('partners', 'public');
            $name = trim((string) ($validated['names'][$index] ?? ''));
            if ($name === '') {
                $name = Str::title(str_replace(['-', '_'], ' ', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)));
            }

            $asset = MediaAsset::create([
                'disk' => 'public',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType() ?? 'image/png',
                'size' => $file->getSize(),
                'alt' => $name.' logo',
                'folder' => 'partners',
                'consent_status' => ConsentStatus::NotRequired,
                'is_private' => false,
            ]);

            $partner = Partner::create([
                'name' => $name,
                'logo_id' => $asset->id,
                'category' => 'day-partner',
                'sort_order' => $maxSort + $index + 1,
                'verification_status' => VerificationStatus::Verified,
                'status' => PublishStatus::Published,
                'published_at' => now(),
            ]);

            $impactStory->partners()->syncWithoutDetaching([$partner->id]);

            $created[] = $this->partnerPayload($partner->load('logo'));
        }

        return response()->json([
            'message' => count($created) === 1 ? '1 partner logo uploaded.' : count($created).' partner logos uploaded.',
            'items' => $created,
        ]);
    }

    public function attachPartner(Request $request, ImpactStory $impactStory): JsonResponse
    {
        $this->authorize('update', $impactStory);

        $validated = $request->validate([
            'partner_id' => ['required', 'integer', 'exists:partners,id'],
        ]);

        $partner = Partner::query()->with('logo')->findOrFail($validated['partner_id']);
        $impactStory->partners()->syncWithoutDetaching([$partner->id]);

        return response()->json([
            'message' => 'Partner attached.',
            'item' => $this->partnerPayload($partner),
        ]);
    }

    public function updateStoryPartner(Request $request, ImpactStory $impactStory, Partner $partner): JsonResponse
    {
        $this->authorize('update', $impactStory);
        abort_unless($impactStory->partners()->where('partners.id', $partner->id)->exists(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $partner->update(['name' => $validated['name']]);

        return response()->json([
            'message' => 'Partner name saved.',
            'item' => $this->partnerPayload($partner->fresh('logo')),
        ]);
    }

    public function detachPartner(ImpactStory $impactStory, Partner $partner): JsonResponse
    {
        $this->authorize('update', $impactStory);

        $impactStory->partners()->detach($partner->id);

        return response()->json([
            'message' => 'Partner removed from this story.',
        ]);
    }

    private function partnerPayload(Partner $partner): array
    {
        return [
            'id' => $partner->id,
            'name' => $partner->name,
            'url' => $partner->logo?->publicUrl(),
            'alt' => $partner->logo?->alt ?: ($partner->name.' logo'),
            'edit_url' => route('admin.partners.edit', $partner),
        ];
    }

    private function ensureGallery(ImpactStory $story): Gallery
    {
        if ($story->gallery_id) {
            return $story->gallery()->with(['items.mediaAsset'])->firstOrFail();
        }

        $baseSlug = Str::slug(($story->slug ?: $story->title).'-gallery');
        $slug = $baseSlug;
        $suffix = 2;
        while (Gallery::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        $gallery = Gallery::create([
            'title' => $story->title.' Gallery',
            'slug' => $slug,
            'description' => 'Photo gallery for '.$story->title,
            'location' => $story->location,
            'gallery_date' => $story->story_date,
            'status' => PublishStatus::Published,
            'published_at' => now(),
            'requires_safeguarding' => false,
            'safeguarding_status' => SafeguardingStatus::NotRequired,
        ]);

        $story->update(['gallery_id' => $gallery->id]);

        return $gallery->load(['items.mediaAsset']);
    }
}
