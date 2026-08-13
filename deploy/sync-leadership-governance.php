<?php

use App\Enums\PublishStatus;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Redirect;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$navUpdated = NavigationItem::query()
    ->where('url', '/about/leadership')
    ->update(['label' => 'Leadership & Governance']);

$govRemoved = 0;
NavigationItem::query()
    ->where('url', '/about/governance')
    ->get()
    ->each(function (NavigationItem $item) use (&$govRemoved) {
        $item->forceDelete();
        $govRemoved++;
    });

Redirect::updateOrCreate(
    ['from_path' => '/about/governance'],
    [
        'to_path' => '/about/leadership#governance',
        'status_code' => 301,
        'is_active' => true,
    ]
);

$page = Page::query()->where('slug', 'about-leadership')->first()
    ?? Page::query()->where('slug', 'leadership-governance')->first();
if ($page) {
    if ($page->slug !== 'about-leadership'
        && ! Page::query()->where('slug', 'about-leadership')->where('id', '!=', $page->id)->exists()) {
        $page->slug = 'about-leadership';
    }
    $page->title = 'Leadership & Governance';
    $page->excerpt = 'Meet the people guiding ASNEN, and the accountability structures that keep the work honest.';
    $page->saveQuietly();
}

$archived = Page::query()->where('slug', 'about-governance')->update([
    'status' => PublishStatus::Archived,
    'unpublished_at' => now(),
]);

echo "nav_updated={$navUpdated} gov_removed={$govRemoved} page=".($page?->id ?? 'missing')." archived={$archived}\n";
