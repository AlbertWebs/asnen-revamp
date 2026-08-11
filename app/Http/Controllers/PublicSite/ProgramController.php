<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\QueriesPublicContent;
use App\Http\Controllers\PublicSite\Concerns\ResolvesPageBanners;
use App\Models\Program;
use App\Repositories\PageRepository;
use App\Services\HtmlSanitizer;

class ProgramController extends Controller
{
    use QueriesPublicContent;
    use ResolvesPageBanners;

    public function __construct(
        private PageRepository $pages,
        private HtmlSanitizer $sanitizer,
    ) {}

    public function index()
    {
        $page = $this->pages->findBySlug('what-we-do');
        $programs = Program::published()->orderBy('sort_order')->with('featuredImage')->get();

        return view('public.programs.index', [
            'page' => $page,
            'programs' => $programs,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function show(string $slug)
    {
        $program = Program::published()
            ->with('featuredImage')
            ->where('slug', $slug)
            ->firstOrFail();

        $page = $this->pages->findBySlug('what-we-do-'.$slug)
            ?? $this->pages->findBySlug('what-we-do');

        $allPrograms = Program::published()
            ->orderBy('sort_order')
            ->get(['id', 'title', 'slug', 'summary', 'sort_order']);

        $relatedStories = $program->impactStories()
            ->published()
            ->with('featuredImage')
            ->latest('story_date')
            ->limit(3)
            ->get();

        return view('public.programs.show', [
            'program' => $program,
            'page' => $page,
            'sanitizer' => $this->sanitizer,
            'allPrograms' => $allPrograms,
            'otherPrograms' => $allPrograms->where('slug', '!=', $program->slug)->take(4)->values(),
            'relatedStories' => $relatedStories,
            'bannerImages' => $this->resolveBannerImages($page, $program->featuredImage),
        ]);
    }
}
