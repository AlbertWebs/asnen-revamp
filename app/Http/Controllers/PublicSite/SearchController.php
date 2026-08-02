<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Event;
use App\Models\FormSubmission;
use App\Models\ImpactStory;
use App\Models\Page;
use App\Models\Program;
use App\Models\Publication;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        $results = collect();

        if ($query !== '') {
            $results = collect()
                ->merge(
                    Page::published()
                        ->where(function ($q) use ($query) {
                            $q->where('title', 'like', "%{$query}%")
                                ->orWhere('excerpt', 'like', "%{$query}%");
                        })
                        ->limit(5)
                        ->get()
                        ->map(fn ($item) => ['type' => 'Page', 'title' => $item->title, 'url' => $this->pageUrl($item->slug)])
                )
                ->merge(
                    Program::published()
                        ->where('title', 'like', "%{$query}%")
                        ->limit(5)
                        ->get()
                        ->map(fn ($item) => ['type' => 'Program', 'title' => $item->title, 'url' => route('site.programs.show', $item->slug)])
                )
                ->merge(
                    ImpactStory::published()
                        ->where('title', 'like', "%{$query}%")
                        ->limit(5)
                        ->get()
                        ->map(fn ($item) => ['type' => 'Impact Story', 'title' => $item->title, 'url' => route('site.impact.stories.show', $item->slug)])
                )
                ->merge(
                    Article::published()
                        ->where('title', 'like', "%{$query}%")
                        ->limit(5)
                        ->get()
                        ->map(fn ($item) => ['type' => 'News', 'title' => $item->title, 'url' => route('site.resources.news.show', $item->slug)])
                )
                ->merge(
                    Publication::published()
                        ->where('title', 'like', "%{$query}%")
                        ->limit(5)
                        ->get()
                        ->map(fn ($item) => ['type' => 'Publication', 'title' => $item->title, 'url' => route('site.resources.publications.show', $item->slug)])
                )
                ->merge(
                    Event::published()
                        ->where('title', 'like', "%{$query}%")
                        ->limit(5)
                        ->get()
                        ->map(fn ($item) => ['type' => 'Event', 'title' => $item->title, 'url' => route('site.events.show', $item->slug)])
                );
        }

        return view('public.search', compact('query', 'results'));
    }

    protected function pageUrl(string $slug): string
    {
        if ($slug === 'home') {
            return route('site.home');
        }

        return '/'.str_replace('-', '/', preg_replace('/^(about|impact|get-involved)-/', '$1/', $slug) ?? $slug);
    }
}
