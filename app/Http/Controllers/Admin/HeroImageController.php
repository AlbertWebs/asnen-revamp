<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\PageBlock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroImageController extends Controller
{
    public function edit(): View
    {
        $this->authorizeHeroAccess();

        $page = $this->homePage();
        $block = $this->heroBlock($page);
        $selectedIds = $this->selectedIds($block);

        $images = MediaAsset::query()
            ->where('mime', 'like', 'image/%')
            ->latest('id')
            ->limit(250)
            ->get();

        $selectedImages = collect($selectedIds)
            ->map(fn (int $id) => $images->firstWhere('id', $id) ?? MediaAsset::query()->find($id))
            ->filter()
            ->values();

        return view('admin.hero-images.edit', [
            'page' => $page,
            'block' => $block,
            'images' => $images,
            'selectedIds' => $selectedIds,
            'selectedImages' => $selectedImages,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorizeHeroAccess();

        $validated = $request->validate([
            'image_ids' => ['nullable', 'array'],
            'image_ids.*' => ['integer', 'exists:media_assets,id'],
        ]);

        $ids = collect($validated['image_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $page = $this->homePage();
        $block = $this->heroBlock($page, createIfMissing: true);
        $content = $block->content ?? [];
        $content['image_ids'] = $ids;
        $content['image_id'] = $ids[0] ?? null;
        $block->content = $content;
        $block->save();

        return redirect()
            ->route('admin.hero-images.edit')
            ->with('success', count($ids) === 0
                ? 'Home hero images cleared.'
                : 'Home hero carousel updated with '.count($ids).' image'.(count($ids) === 1 ? '' : 's').'.');
    }

    private function authorizeHeroAccess(): void
    {
        $user = auth()->user();
        abort_unless(
            $user?->can('pages.update') || $user?->can('media.update'),
            403
        );
    }

    private function homePage(): Page
    {
        return Page::query()->where('slug', 'home')->firstOrFail();
    }

    private function heroBlock(Page $page, bool $createIfMissing = false): ?PageBlock
    {
        $block = PageBlock::query()
            ->where('page_id', $page->id)
            ->where('type', 'hero')
            ->orderBy('sort_order')
            ->first();

        if ($block || ! $createIfMissing) {
            return $block;
        }

        return PageBlock::create([
            'page_id' => $page->id,
            'type' => 'hero',
            'sort_order' => 0,
            'is_visible' => true,
            'content' => [
                'headline' => '',
                'supporting_text' => '',
                'image_id' => null,
                'image_ids' => [],
                'primary_cta' => ['label' => '', 'url' => ''],
                'secondary_cta' => ['label' => '', 'url' => ''],
            ],
        ]);
    }

    /**
     * @return array<int, int>
     */
    private function selectedIds(?PageBlock $block): array
    {
        if (! $block) {
            return [];
        }

        $ids = collect($block->content['image_ids'] ?? [])
            ->filter(fn ($id) => filled($id) && $id !== 'null')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($ids === [] && ! empty($block->content['image_id'])) {
            $ids = [(int) $block->content['image_id']];
        }

        return $ids;
    }
}
