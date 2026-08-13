<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Event;
use App\Models\ImpactStory;
use App\Models\Page;
use App\Models\Program;
use App\Models\Publication;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([
            route('site.home'),
            route('site.about.who-we-are'),
            route('site.about.leadership'),
            route('site.about.partners'),
            route('site.programs.index'),
            route('site.impact.overview'),
            route('site.impact.stories'),
            route('site.impact.reports'),
            route('site.impact.regions'),
            route('site.events.index'),
            route('site.resources.index'),
            route('site.get-involved.index'),
            route('site.resources.gallery.index'),
            route('site.contact'),
        ]);

        Page::published()->pluck('slug')->each(function (string $slug) use ($urls) {
            if (! in_array($slug, ['home', 'about-governance', 'about-mission-vision-values', 'about-our-story', 'vision-mission-values', 'impact-komolion'], true)) {
                $urls->push(url('/'.str_replace('-', '/', $slug)));
            }
        });

        Program::published()->pluck('slug')->each(fn ($slug) => $urls->push(route('site.programs.show', $slug)));
        ImpactStory::published()->pluck('slug')->each(fn ($slug) => $urls->push(route('site.impact.stories.show', $slug)));
        Article::published()->pluck('slug')->each(fn ($slug) => $urls->push(route('site.resources.news.show', $slug)));
        Publication::published()->pluck('slug')->each(fn ($slug) => $urls->push(route('site.resources.publications.show', $slug)));
        Event::published()->pluck('slug')->each(fn ($slug) => $urls->push(route('site.events.show', $slug)));

        $content = view('public.sitemap', ['urls' => $urls->unique()->values()])->render();

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }
}
