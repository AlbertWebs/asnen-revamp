<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\ResolvesPageBanners;
use App\Http\Controllers\PublicSite\Concerns\RespondsToAjaxForms;
use App\Http\Requests\PublicSite\ToolkitRequestFormRequest;
use App\Models\Article;
use App\Models\FormDefinition;
use App\Models\Publication;
use App\Models\Webinar;
use App\Repositories\PageRepository;
use App\Services\FormSubmissionService;
use App\Services\HtmlSanitizer;

class ResourceController extends Controller
{
    use ResolvesPageBanners;
    use RespondsToAjaxForms;

    public function __construct(
        private HtmlSanitizer $sanitizer,
        private FormSubmissionService $forms,
        private PageRepository $pages,
    ) {}

    public function index()
    {
        $page = $this->pages->findBySlug('resources');

        return view('public.resources.index', [
            'page' => $page,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function publications()
    {
        $page = $this->pages->findBySlug('resources-publications')
            ?? $this->pages->findBySlug('resources');

        $publications = Publication::published()
            ->with(['cover', 'file'])
            ->latest('published_at')
            ->paginate(12);

        return view('public.resources.publications', [
            'page' => $page,
            'publications' => $publications,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function showPublication(string $slug)
    {
        $publication = Publication::published()->where('slug', $slug)->with(['cover', 'file'])->firstOrFail();
        $canRequestFile = ! $publication->file;
        $page = $this->pages->findBySlug('resources-publications')
            ?? $this->pages->findBySlug('resources');

        return view('public.resources.publication-show', [
            'publication' => $publication,
            'canRequestFile' => $canRequestFile,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page, $publication->cover),
        ]);
    }

    public function requestToolkit(ToolkitRequestFormRequest $request, string $slug)
    {
        $publication = Publication::published()->where('slug', $slug)->firstOrFail();

        abort_unless(! $publication->file, 404);

        $data = $request->formData();
        $data['publication_slug'] = $publication->slug;
        $data['publication_title'] = $publication->title;

        $form = FormDefinition::where('slug', 'toolkit-request')->where('is_active', true)->firstOrFail();
        $submission = $this->forms->store($form, $data, $request);

        return $this->formSuccessResponse(
            $request,
            $submission->confirmation_token,
            'toolkit-request',
            $form->success_message
        );
    }

    public function downloadPublication(string $slug)
    {
        $publication = Publication::published()->where('slug', $slug)->with('file')->firstOrFail();

        abort_unless($publication->file?->path, 404);

        $disk = $publication->file->disk ?: 'public';
        abort_unless(\Illuminate\Support\Facades\Storage::disk($disk)->exists($publication->file->path), 404);

        $publication->increment('download_count');

        $downloadName = $publication->file->filename
            ?: \Illuminate\Support\Str::slug($publication->title).'.pdf';

        return \Illuminate\Support\Facades\Storage::disk($disk)->download(
            $publication->file->path,
            $downloadName
        );
    }

    public function toolkits()
    {
        $page = $this->pages->findBySlug('resources-toolkits')
            ?? $this->pages->findBySlug('resources');

        $publications = Publication::published()
            ->with(['cover', 'file'])
            ->whereIn('category', ['toolkit', 'guide'])
            ->orderByDesc('year')
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('public.resources.toolkits', [
            'page' => $page,
            'publications' => $publications,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function webinarLibrary()
    {
        $page = $this->pages->findBySlug('resources-webinars')
            ?? $this->pages->findBySlug('resources');

        $webinars = Webinar::published()
            ->with('featuredImage')
            ->latest('held_at')
            ->paginate(12);

        return view('public.resources.webinar-library', [
            'page' => $page,
            'webinars' => $webinars,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function news()
    {
        $page = $this->pages->findBySlug('resources-news')
            ?? $this->pages->findBySlug('resources');

        $articles = Article::published()
            ->with('featuredImage')
            ->latest('published_at')
            ->paginate(12);

        return view('public.resources.news', [
            'page' => $page,
            'articles' => $articles,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function showArticle(string $slug)
    {
        $article = Article::published()->where('slug', $slug)->with('featuredImage')->firstOrFail();
        $page = $this->pages->findBySlug('resources-news')
            ?? $this->pages->findBySlug('resources');

        return view('public.resources.article-show', [
            'article' => $article,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page, $article->featuredImage),
        ]);
    }
}
