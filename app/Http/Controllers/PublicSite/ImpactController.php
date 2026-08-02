<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\QueriesPublicContent;
use App\Models\ImpactStory;
use App\Models\Publication;
use App\Models\Region;
use App\Repositories\PageRepository;
use App\Services\HtmlSanitizer;

class ImpactController extends Controller
{
    use QueriesPublicContent;

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
            ->where('slug', 'komolion-2023-disability-assessment-medical-camp')
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
            ['sanitizer' => $this->sanitizer]
        ));
    }

    public function komolion()
    {
        $story = ImpactStory::published()
            ->where('slug', 'komolion-2023-disability-assessment-medical-camp')
            ->with(['outcomes', 'partners.logo', 'programs', 'gallery.items.mediaAsset', 'featuredImage'])
            ->firstOrFail();

        $page = $this->pages->findBySlug('impact-komolion');

        return view('public.impact.komolion', array_merge(compact('story', 'page'), ['sanitizer' => $this->sanitizer]));
    }

    public function stories()
    {
        $page = $this->pages->findBySlug('impact-stories');

        $featuredStory = ImpactStory::published()
            ->where('slug', 'komolion-2023-disability-assessment-medical-camp')
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
        ]);
    }

    public function showStory(string $slug)
    {
        $story = ImpactStory::published()
            ->where('slug', $slug)
            ->with(['outcomes', 'partners.logo', 'programs'])
            ->firstOrFail();

        return view('public.impact.story-show', array_merge(compact('story'), ['sanitizer' => $this->sanitizer]));
    }

    public function reports()
    {
        $page = $this->pages->findBySlug('impact-reports');
        abort_unless($page, 404);
        $page->load('blocks');

        $introHtml = $page->blocks
            ->firstWhere('type', 'rich_text')
            ?->content['body'] ?? null;

        $reports = Publication::published()
            ->with(['cover', 'file'])
            ->whereIn('category', ['report', 'annual_report', 'conference_report', 'impact_report'])
            ->orderByDesc('year')
            ->orderByDesc('published_at')
            ->get();

        return view('public.impact.reports', [
            'page' => $page,
            'introHtml' => $introHtml,
            'reports' => $reports,
            'sanitizer' => $this->sanitizer,
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
        ]);
    }
}
