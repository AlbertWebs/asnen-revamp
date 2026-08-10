<?php

use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\PageBlock;

$page = Page::where('slug', 'about-partners')->first();
if ($page) {
    $page->title = 'Collaborators';
    $page->excerpt = 'Organisations collaborating with ASNEN to advance inclusion across Africa.';
    $page->slug = 'about-partners';
    $page->saveQuietly();

    PageBlock::updateOrCreate(
        ['page_id' => $page->id, 'type' => 'rich_text'],
        [
            'sort_order' => 1,
            'is_visible' => true,
            'content' => [
                'body' => '<p>ASNEN\'s impact is strengthened through collaboration with schools, NGOs, health institutions, and community organisations. Collaborator profiles are listed here once names, descriptions, logos, and URLs have been verified by administrators.</p>',
            ],
        ]
    );
}

$updated = NavigationItem::query()
    ->where('url', '/about/partners')
    ->where('label', 'Partners')
    ->update(['label' => 'Collaborators']);

echo "page=".($page?->id ?? 'missing')." nav_updated={$updated}\n";
