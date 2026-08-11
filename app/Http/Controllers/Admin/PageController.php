<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PublishStatus;
use App\Http\Controllers\Admin\Concerns\SyncsPageBlocks;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageStoreRequest;
use App\Http\Requests\Admin\PageUpdateRequest;
use App\Models\MediaAsset;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    use SyncsPageBlocks;

    public function index(): View
    {
        $this->authorize('viewAny', Page::class);

        $pages = Page::query()
            ->with('author')
            ->latest()
            ->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        $this->authorize('create', Page::class);

        $page = new Page(['status' => PublishStatus::Draft]);

        return view('admin.pages.edit', [
            'page' => $page,
            'blocks' => [],
            'mediaOptions' => $this->mediaOptions(),
        ]);
    }

    public function store(PageStoreRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('blocks');
        $data['author_id'] = $request->user()->id;

        $page = Page::create($data);

        if ($request->filled('blocks')) {
            $this->syncPageBlocks($page, $request->input('blocks', []));
        }

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page created successfully.');
    }

    public function edit(Page $page): View
    {
        $this->authorize('update', $page);

        $page->load('blocks');

        $blocks = $page->blocks->map(function ($block) {
            $content = $block->content ?? [];
            if (! is_array($content)) {
                $content = [];
            }

            // Ensure nested CTA objects exist so Alpine x-model bindings work.
            if (($block->type ?? '') === 'hero') {
                $content['headline'] = $content['headline'] ?? ($content['heading'] ?? '');
                $content['supporting_text'] = $content['supporting_text'] ?? ($content['body'] ?? '');
                $content['image_id'] = $content['image_id'] ?? null;
                $content['image_alt'] = $content['image_alt'] ?? null;
                $imageIds = collect($content['image_ids'] ?? [])
                    ->filter(fn ($id) => filled($id))
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->all();
                if ($imageIds === [] && ! empty($content['image_id'])) {
                    $imageIds = [(int) $content['image_id']];
                }
                $content['image_ids'] = $imageIds;
                $content['primary_cta'] = array_merge(
                    ['label' => '', 'url' => ''],
                    is_array($content['primary_cta'] ?? null) ? $content['primary_cta'] : []
                );
                $content['secondary_cta'] = array_merge(
                    ['label' => '', 'url' => ''],
                    is_array($content['secondary_cta'] ?? null) ? $content['secondary_cta'] : []
                );
            } else {
                $content['heading'] = $content['heading'] ?? '';
                $content['body'] = $content['body'] ?? '';
                if (array_key_exists('image_id', $content) || in_array($block->type, ['who_we_are', 'image_text'], true)) {
                    $content['image_id'] = $content['image_id'] ?? null;
                }
            }

            return [
                'type' => $block->type,
                'is_visible' => (bool) $block->is_visible,
                'content' => $content,
                'settings' => $block->settings ?? [],
                'anchor_id' => $block->anchor_id,
            ];
        })->values()->all();

        return view('admin.pages.edit', [
            'page' => $page,
            'blocks' => $blocks,
            'mediaOptions' => $this->mediaOptions(),
        ]);
    }

    public function update(PageUpdateRequest $request, Page $page): RedirectResponse
    {
        $page->update($request->safe()->except('blocks'));

        if ($request->has('blocks')) {
            $this->syncPageBlocks($page, $request->input('blocks', []));
        }

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $this->authorize('delete', $page);

        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    public function publish(Page $page): RedirectResponse
    {
        $this->authorize('publish', $page);

        if (! $page->canPublishSafely()) {
            return back()->with('error', 'Safeguarding approval is required before publishing.');
        }

        $page->publish();

        return back()->with('success', 'Page published.');
    }

    public function unpublish(Page $page): RedirectResponse
    {
        $this->authorize('publish', $page);

        $page->unpublish();

        return back()->with('success', 'Page unpublished.');
    }

    /**
     * @return array<int, array{id: int|null, label: string, url: string|null}>
     */
    private function mediaOptions(): array
    {
        return MediaAsset::query()
            ->where('mime', 'like', 'image/%')
            ->latest('id')
            ->limit(250)
            ->get()
            ->map(fn (MediaAsset $asset) => [
                'id' => $asset->id,
                'label' => ($asset->alt ?: $asset->filename).' (#'.$asset->id.')',
                'url' => $asset->publicUrl(),
            ])
            ->values()
            ->all();
    }
}
