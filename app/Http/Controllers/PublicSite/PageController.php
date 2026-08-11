<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\QueriesPublicContent;
use App\Models\Faq;
use App\Repositories\PageRepository;
use App\Services\HtmlSanitizer;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    use QueriesPublicContent;

    public function __construct(
        private PageRepository $pages,
        private HtmlSanitizer $sanitizer,
    ) {}

    public function show(string $path)
    {
        $page = $this->pages->findByPath($path);

        abort_unless($page, 404);

        $viewData = [
            'page' => $page,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $page->bannerImages(),
        ];

        if (Str::startsWith($page->slug, 'about-') || $page->slug === 'vision-mission-values') {
            return $this->showAboutPage($page);
        }

        if ($page->slug === 'faqs') {
            $viewData['faqs'] = Faq::published()->orderBy('sort_order')->get();
        }

        return view('public.page', $viewData);
    }

    public function aboutRedirect()
    {
        return redirect()->route('site.about.who-we-are');
    }

    private function showAboutPage($page): View
    {
        $page->loadMissing('blocks');
        $introHtml = $page->blocks->firstWhere('type', 'rich_text')?->content['body'] ?? null;

        return match ($page->slug) {
            'about-who-we-are' => view('public.about.who-we-are', [
                'page' => $page,
                'sanitizer' => $this->sanitizer,
                'bannerImages' => $page->bannerImages(),
            ]),
            'about-mission-vision-values', 'vision-mission-values' => view('public.about.mission', [
                'page' => $page,
                'sanitizer' => $this->sanitizer,
                'introHtml' => $introHtml,
                'sections' => $this->parseHeadingSections($introHtml ?? ''),
                'bannerImages' => $page->bannerImages(),
            ]),
            'about-our-story' => view('public.about.story', [
                'page' => $page,
                'sanitizer' => $this->sanitizer,
                'introHtml' => $introHtml,
                'bannerImages' => $page->bannerImages(),
            ]),
            'about-leadership' => view('public.about.leadership', [
                'page' => $page,
                'sanitizer' => $this->sanitizer,
                'introHtml' => $introHtml,
                'teamMembers' => $this->publishedTeamMembers()->with('photo')->get(),
                'bannerImages' => $page->bannerImages(),
            ]),
            'about-governance' => view('public.about.governance', [
                'page' => $page,
                'sanitizer' => $this->sanitizer,
                'introHtml' => $introHtml,
                'bannerImages' => $page->bannerImages(),
            ]),
            'about-partners' => view('public.about.partners', [
                'page' => $page,
                'sanitizer' => $this->sanitizer,
                'introHtml' => $introHtml,
                'partners' => $this->verifiedPublishedPartners()->with('logo')->get(),
                'bannerImages' => $page->bannerImages(),
            ]),
            default => view('public.page', [
                'page' => $page,
                'sanitizer' => $this->sanitizer,
                'bannerImages' => $page->bannerImages(),
            ]),
        };
    }

    /**
     * @return array<int, array{heading: string, html: string}>
     */
    private function parseHeadingSections(string $html): array
    {
        if ($html === '') {
            return [];
        }

        if (! preg_match_all('/<h2[^>]*>(.*?)<\/h2>(.*?)(?=<h2[^>]*>|$)/is', $html, $matches, PREG_SET_ORDER)) {
            return [];
        }

        return collect($matches)
            ->map(fn (array $match) => [
                'heading' => trim(html_entity_decode(strip_tags($match[1]))),
                'html' => trim($match[2]),
            ])
            ->filter(fn (array $section) => $section['heading'] !== '')
            ->values()
            ->all();
    }
}
