<?php

use App\Enums\PublishStatus;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Redirect;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$removed = 0;
NavigationItem::query()
    ->whereIn('url', ['/about/mission-vision-values', '/about/our-story'])
    ->get()
    ->each(function (NavigationItem $item) use (&$removed) {
        $item->forceDelete();
        $removed++;
    });

NavigationItem::query()
    ->where('url', '/about/who-we-are')
    ->update(['label' => 'Who We Are']);

foreach ([
    '/about/mission-vision-values' => '/about/who-we-are#vision',
    '/about/our-story' => '/about/who-we-are#story',
] as $from => $to) {
    Redirect::updateOrCreate(
        ['from_path' => $from],
        [
            'to_path' => $to,
            'status_code' => 301,
            'is_active' => true,
        ]
    );
}

$page = Page::query()->where('slug', 'about-who-we-are')->first();
if ($page) {
    $page->title = 'Who We Are';
    $page->excerpt = 'A pan-African coalition for inclusive education — our identity, vision, and story.';
    $page->saveQuietly();
}

$archived = Page::query()->whereIn('slug', [
    'about-mission-vision-values',
    'about-our-story',
])->update([
    'status' => PublishStatus::Archived,
    'unpublished_at' => now(),
]);

echo "nav_removed={$removed} page=".($page?->id ?? 'missing')." archived={$archived}\n";
