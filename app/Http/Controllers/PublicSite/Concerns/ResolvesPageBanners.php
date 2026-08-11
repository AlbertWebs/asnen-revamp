<?php

namespace App\Http\Controllers\PublicSite\Concerns;

use App\Models\Page;
use Illuminate\Support\Collection;

trait ResolvesPageBanners
{
    /**
     * Banner images for a CMS page, optionally falling back to a featured media asset.
     *
     * @param  mixed  $featured
     */
    protected function resolveBannerImages(?Page $page, $featured = null): Collection
    {
        $images = $page?->bannerImages() ?? collect();

        if ($images->isNotEmpty()) {
            return $images;
        }

        if ($featured && method_exists($featured, 'publicUrl') && filled($featured->publicUrl())) {
            return collect([$featured]);
        }

        return collect();
    }
}
