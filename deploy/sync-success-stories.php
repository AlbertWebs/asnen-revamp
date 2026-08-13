<?php

use App\Enums\PublishStatus;
use App\Models\ImpactStory;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Redirect;
use App\Models\Region;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$removed = 0;
NavigationItem::query()
    ->where('url', '/impact/komolion')
    ->get()
    ->each(function (NavigationItem $item) use (&$removed) {
        $item->forceDelete();
        $removed++;
    });

NavigationItem::query()
    ->where('url', '/impact/stories')
    ->update(['label' => 'Success Stories']);

$storyUrl = '/impact/stories/'.ImpactStory::KOMOLION_SLUG;

Redirect::updateOrCreate(
    ['from_path' => '/impact/komolion'],
    [
        'to_path' => $storyUrl,
        'status_code' => 301,
        'is_active' => true,
    ]
);

$page = Page::query()->where('slug', 'impact-stories')->first();
if ($page) {
    $page->title = 'Success Stories';
    $page->excerpt = 'Komolion and other impact stories from ASNEN programmes and community initiatives.';
    $page->saveQuietly();
}

$archived = Page::query()->where('slug', 'impact-komolion')->update([
    'status' => PublishStatus::Archived,
    'unpublished_at' => now(),
]);

$regions = Region::query()
    ->where('link_url', '/impact/komolion')
    ->update(['link_url' => $storyUrl]);

echo "nav_removed={$removed} page=".($page?->id ?? 'missing')." archived={$archived} regions={$regions}\n";
