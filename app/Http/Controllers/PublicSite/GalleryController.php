<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Repositories\PageRepository;
use App\Services\HtmlSanitizer;

class GalleryController extends Controller
{
    public function __construct(
        private PageRepository $pages,
        private HtmlSanitizer $sanitizer,
    ) {}

    public function index()
    {
        $page = $this->pages->findBySlug('gallery');

        $galleries = Gallery::published()
            ->withCount('items')
            ->with(['coverItem.mediaAsset'])
            ->latest('gallery_date')
            ->paginate(12);

        return view('public.gallery.index', [
            'page' => $page,
            'galleries' => $galleries,
            'sanitizer' => $this->sanitizer,
        ]);
    }

    public function show(string $slug)
    {
        $gallery = Gallery::published()
            ->where('slug', $slug)
            ->with(['items.mediaAsset'])
            ->firstOrFail();

        return view('public.gallery.show', [
            'gallery' => $gallery,
            'sanitizer' => $this->sanitizer,
        ]);
    }
}
