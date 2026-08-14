<?php

/**
 * Create Events & Learning CMS pages so they appear under Admin > Pages.
 * Idempotent. Safe to re-run on deploy.
 */

use App\Enums\PublishStatus;
use App\Enums\SafeguardingStatus;
use App\Enums\VerificationStatus;
use App\Models\Page;
use App\Models\PageBlock;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pages = [
    [
        'slug' => 'events-learning',
        'title' => 'Events & Learning',
        'excerpt' => 'Conferences, webinars, workshops, and community gatherings advancing inclusive education.',
        'body' => '<p>ASNEN convenes learning across the network: upcoming dates, past gatherings, open webinars, and the Ubuntu Conference series.</p>',
    ],
    [
        'slug' => 'events-learning-upcoming',
        'title' => 'Upcoming Events',
        'excerpt' => 'Pre-Registration Webinar · 23 November 2026 · Disability Registration Day · 5 December 2026',
        'body' => '<p>Inclusion for all, in all. No child left behind. Partner with ASNEN on this season\'s registration initiative with NCPWD and the Ministry of Health.</p>',
    ],
    [
        'slug' => 'events-learning-past',
        'title' => 'Past Events',
        'excerpt' => 'Past gatherings, camps, and learning events from across the ASNEN network.',
        'body' => '<p>Browse recaps and materials from previous ASNEN events.</p>',
    ],
    [
        'slug' => 'events-learning-webinars',
        'title' => 'Webinars',
        'excerpt' => 'Open webinars and recordings on inclusive education, caregiving, and disability inclusion.',
        'body' => '<p>Watch and register for ASNEN webinars that share practical knowledge with families, educators, and partners.</p>',
    ],
    [
        'slug' => 'events-learning-ubuntu-conference',
        'title' => 'Ubuntu Conference',
        'excerpt' => 'ASNEN\'s flagship gathering for inclusive education across Africa.',
        'body' => '<p>The Ubuntu Conference convenes educators, caregivers, advocates, and partners around African, homegrown models of inclusive practice.</p>',
    ],
];

foreach ($pages as $item) {
    $page = Page::updateOrCreate(
        ['slug' => $item['slug']],
        [
            'title' => $item['title'],
            'template' => 'default',
            'excerpt' => $item['excerpt'],
            'status' => PublishStatus::Published,
            'published_at' => now(),
            'verification_status' => VerificationStatus::Verified,
            'requires_safeguarding' => false,
            'safeguarding_status' => SafeguardingStatus::NotRequired,
        ]
    );

    if ($page->slug !== $item['slug']) {
        $page->slug = $item['slug'];
        $page->saveQuietly();
    }

    PageBlock::updateOrCreate(
        ['page_id' => $page->id, 'type' => 'rich_text'],
        [
            'sort_order' => 1,
            'is_visible' => true,
            'content' => ['body' => $item['body']],
        ]
    );

    echo "synced {$page->slug} id={$page->id}\n";
}
