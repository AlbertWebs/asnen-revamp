<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Publication;
use App\Models\Webinar;
use App\Services\HtmlSanitizer;

class ResourceController extends Controller
{
    public function __construct(private HtmlSanitizer $sanitizer) {}

    public function index()
    {
        return view('public.resources.index', ['sanitizer' => $this->sanitizer]);
    }

    public function publications()
    {
        $publications = Publication::published()
            ->with(['cover', 'file'])
            ->latest('published_at')
            ->paginate(12);

        return view('public.resources.publications', array_merge(compact('publications'), ['sanitizer' => $this->sanitizer]));
    }

    public function showPublication(string $slug)
    {
        $publication = Publication::published()->where('slug', $slug)->with(['cover', 'file'])->firstOrFail();

        return view('public.resources.publication-show', array_merge(compact('publication'), ['sanitizer' => $this->sanitizer]));
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
        $publications = Publication::published()
            ->with(['cover', 'file'])
            ->whereIn('category', ['toolkit', 'guide'])
            ->orderByDesc('year')
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('public.resources.toolkits', [
            'publications' => $publications,
            'sanitizer' => $this->sanitizer,
        ]);
    }

    public function webinarLibrary()
    {
        $webinars = Webinar::published()
            ->with('featuredImage')
            ->latest('held_at')
            ->paginate(12);

        return view('public.resources.webinar-library', [
            'webinars' => $webinars,
            'sanitizer' => $this->sanitizer,
        ]);
    }

    public function news()
    {
        $articles = Article::published()
            ->with('featuredImage')
            ->latest('published_at')
            ->paginate(12);

        return view('public.resources.news', [
            'articles' => $articles,
            'sanitizer' => $this->sanitizer,
        ]);
    }

    public function showArticle(string $slug)
    {
        $article = Article::published()->where('slug', $slug)->with('featuredImage')->firstOrFail();

        return view('public.resources.article-show', array_merge(compact('article'), ['sanitizer' => $this->sanitizer]));
    }
}
