<?php

use App\Models\Page;
use App\Models\PageBlock;

$page = Page::where('slug', 'home')->firstOrFail();
$block = PageBlock::where('page_id', $page->id)->where('type', 'featured_events')->first();

if (! $block) {
    $maxOrder = (int) PageBlock::where('page_id', $page->id)->max('sort_order');
    $block = PageBlock::create([
        'page_id' => $page->id,
        'type' => 'featured_events',
        'sort_order' => $maxOrder + 1,
        'is_visible' => true,
        'content' => [],
    ]);
}

$block->update([
    'is_visible' => true,
    'content' => [
        'heading' => 'Upcoming events',
        'intro' => 'Conferences, webinars, and gatherings coming up across the ASNEN network.',
        'limit' => 3,
        'show_upcoming_only' => true,
        'show_past_only' => false,
        'fallback_to_past' => true,
    ],
]);

echo "updated block={$block->id}\n";
