<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PublicSite\Concerns\ResolvesPageBanners;
use App\Models\Gallery;
use App\Repositories\PageRepository;
use App\Services\HtmlSanitizer;

class GalleryController extends Controller
{
    use ResolvesPageBanners;

    public function __construct(
        private PageRepository $pages,
        private HtmlSanitizer $sanitizer,
    ) {}

    public function index()
    {
        $page = $this->pages->findBySlug('gallery')
            ?? $this->pages->findBySlug('resources-gallery');

        $galleries = Gallery::published()
            ->withCount('items')
            ->with(['coverItem.mediaAsset'])
            ->latest('gallery_date')
            ->paginate(12);

        return view('public.gallery.index', [
            'page' => $page,
            'galleries' => $galleries,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $this->resolveBannerImages($page),
        ]);
    }

    public function show(string $slug)
    {
        $gallery = Gallery::published()
            ->where('slug', $slug)
            ->with(['items.mediaAsset', 'coverItem.mediaAsset'])
            ->firstOrFail();

        $page = $this->pages->findBySlug('gallery')
            ?? $this->pages->findBySlug('resources-gallery');

        $itemImages = $gallery->items
            ->map(fn ($item) => $item->mediaAsset)
            ->filter()
            ->values();

        $bannerImages = $this->resolveBannerImages($page, $gallery->coverItem?->mediaAsset);
        if ($bannerImages->isEmpty() && $itemImages->isNotEmpty()) {
            $bannerImages = $itemImages->take(6);
        }

        return view('public.gallery.show', [
            'gallery' => $gallery,
            'sanitizer' => $this->sanitizer,
            'bannerImages' => $bannerImages,
        ]);
    }
}

