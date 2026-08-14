<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\QueriesPublicContent;
use App\Http\Controllers\PublicSite\Concerns\ResolvesPageBanners;
use App\Models\ImpactStory;
use App\Models\Region;
use App\Repositories\PageRepository;
use App\Services\HtmlSanitizer;

class ImpactController extends Controller
{
    use QueriesPublicContent;
    use ResolvesPageBanners;

    public function __construct(
        private PageRepository $pages,
        private HtmlSanitizer $sanitizer,
    ) {}

    public function overview()
    {
        $page = $this->pages->findBySlug('impact');
        $page?->load('blocks');
        $introHtml = $page?->blocks
            ->firstWhere('type', 'rich_text')
            ?->content['body'] ?? null;

        $metrics = $this->verifiedPublishedMetrics()->get();

        $featuredStory = ImpactStory::published()
            ->where('slug', ImpactStory::KOMOLION_SLUG)
            ->with(['outcomes', 'featuredImage', 'partners'])
            ->first();

        if (! $featuredStory) {
            $featuredStory = ImpactStory::published()
                ->with(['outcomes', 'featuredImage', 'partners'])
                ->latest('story_date')
                ->first();
        }

        $stories = ImpactStory::published()
            ->with('featuredImage')
            ->when($featuredStory, fn ($query) => $query->where('id', '!=', $featuredStory->id))
            ->latest('story_date')
            ->limit(3)
            ->get();

        return view('public.impact.overview', array_merge(
            compact('page', 'introHtml', 'metrics', 'featuredStory', 'stories'),
            [
                'sanitizer' => $this->sanitizer,
                'bannerImages' => $this->resolveBannerImages($page, $featuredStory?->featuredImage),
            ]
        ));
    }

    public function komolion()
    {
        return redirect()->route('site.impact.stories.show', ImpactStory::KOMOLION_SLUG, 301);
    }

    public function stories()
    {
        $page = $this->pages->findBySlug('impact-stories');

        $featuredStory = ImpactStory::published()
            ->where('slug', ImpactStory::KOMOLION_SLUG)
            ->with(['outcomes', 'featuredImage', 'partners'])
            ->first();

        if (! $featuredStory) {
            $featuredStory = ImpactStory::published()
                ->with(['outcomes', 'featuredImage', 'partners'])
                ->latest('story_date')
                ->first();
        }

        $stories = ImpactStory::published()
            ->with('featuredImage')
            ->latest('story_date')
            ->paginate(12);

        return view('public.impact.stories', [
            'page' => $page,
            'stories' => $stories,
            'featuredStory' => $featuredStory,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page, $featuredStory?->featuredImage),
        ]);
    }

    public function showStory(string $slug)
    {
        $story = ImpactStory::published()
            ->where('slug', $slug)
            ->with(['outcomes', 'partners.logo', 'programs', 'gallery.items.mediaAsset', 'featuredImage'])
            ->firstOrFail();

        if ($slug === ImpactStory::KOMOLION_SLUG) {
            return $this->komolionView($story);
        }

        $page = $this->pages->findBySlug('impact-stories');

        return view('public.impact.story-show', [
            'story' => $story,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page, $story->featuredImage),
        ]);
    }

    private function komolionView(ImpactStory $story)
    {
        $page = $this->pages->findBySlug('impact-komolion')
            ?? $this->pages->findBySlug('impact-stories')
            ?? $this->pages->findBySlug('impact');

        $galleryImages = collect($story->gallery?->items ?? [])
            ->map(fn ($item) => $item->mediaAsset)
            ->filter()
            ->values();

        $bannerImages = $this->resolveBannerImages($page, $story->featuredImage);
        if ($bannerImages->isEmpty() && $galleryImages->isNotEmpty()) {
            $bannerImages = $galleryImages;
        } elseif ($galleryImages->isNotEmpty() && $bannerImages->count() < 2) {
            $bannerImages = $bannerImages->concat($galleryImages)->unique('id')->values();
        }

        return view('public.impact.komolion', [
            'story' => $story,
            'page' => $page,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $bannerImages,
        ]);
    }

    public function regions()
    {
        $page = $this->pages->findBySlug('impact-regions');
        $page?->load('blocks');

        $regions = Region::published()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('public.impact.regions', [
            'page' => $page,
            'regions' => $regions,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }
}
