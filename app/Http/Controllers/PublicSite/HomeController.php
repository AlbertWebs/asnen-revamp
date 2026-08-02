<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\QueriesPublicContent;
use App\Models\ImpactStory;
use App\Models\Program;
use App\Repositories\PageRepository;

class HomeController extends Controller
{
    use QueriesPublicContent;

    public function __construct(private PageRepository $pages) {}

    public function __invoke()
    {
        $page = $this->pages->findBySlug('home');

        abort_unless($page, 404);

        return view('public.home', [
            'page' => $page,
            'announcement' => $this->activeAnnouncement(),
            'metrics' => $this->verifiedPublishedMetrics()->get(),
            'programs' => Program::published()->orderBy('sort_order')->get(),
            'featuredStory' => ImpactStory::published()
                ->where('slug', 'komolion-2023-disability-assessment-medical-camp')
                ->with(['outcomes', 'partners'])
                ->first(),
            'partners' => $this->verifiedPublishedPartners()->with('logo')->get(),
        ]);
    }
}
