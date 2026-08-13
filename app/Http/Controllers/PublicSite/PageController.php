<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\QueriesPublicContent;
use App\Models\Faq;
use App\Models\Page;
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
                'missionSections' => $this->parseHeadingSections($this->aboutPageBody('about-mission-vision-values')),
                'storyIntroHtml' => $this->aboutPageBody('about-our-story'),
            ]),
            'about-leadership' => view('public.about.leadership', [
                'page' => $page,
                'sanitizer' => $this->sanitizer,
                'introHtml' => $introHtml,
                'governanceIntroHtml' => $this->governanceIntroHtml(),
                'teamMembers' => $this->publishedTeamMembers()->with('photo')->get(),
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

    private function aboutPageBody(string $slug): string
    {
        $aboutPage = Page::query()
            ->where('slug', $slug)
            ->with(['blocks' => fn ($q) => $q->where('is_visible', true)])
            ->first();

        return (string) ($aboutPage?->blocks->firstWhere('type', 'rich_text')?->content['body'] ?? '');
    }

    private function governanceIntroHtml(): string
    {
        $body = $this->aboutPageBody('about-governance');

        return $body !== ''
            ? $body
            : '<p>ASNEN operates with governance practices that support ethical stewardship, safeguarding, financial accountability, and community accountability. Board and governance details are published here once verified by administrators.</p>';
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
