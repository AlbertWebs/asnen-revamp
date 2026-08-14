<?php

/**
 * Create Resources CMS pages so they appear under Admin > Pages.
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
        'slug' => 'resources',
        'title' => 'Resources',
        'excerpt' => 'Reports, toolkits, webinar recordings, news, and gallery from ASNEN.',
        'body' => '<p>Practical materials and updates from the ASNEN network: reports and publications, toolkits and guides, the webinar library, news, and the gallery.</p>',
    ],
    [
        'slug' => 'resources-publications',
        'title' => 'Reports & Publications',
        'excerpt' => 'Download ASNEN reports, toolkits, and publications documenting programmes, learning, and verified progress.',
        'body' => '<p>Annual reports, conference reports, toolkits, and other publications from ASNEN programmes and learning. Individual files are managed under Publications in admin.</p>',
    ],
    [
        'slug' => 'resources-toolkits',
        'title' => 'Toolkits and Guides',
        'excerpt' => 'Practical guides for educators, caregivers, and partners building inclusive education across Africa.',
        'body' => '<p>Toolkits and guides designed for African classrooms, clinics, and homes. Files that are not available for direct download can be requested from the publication page.</p>',
    ],
    [
        'slug' => 'resources-webinars',
        'title' => 'Videos / Webinar Library',
        'excerpt' => 'Video and webinar recordings from ASNEN learning sessions.',
        'body' => '<p>Watch recorded webinars and related video from ASNEN programmes. Individual webinars are managed under Webinars in admin.</p>',
    ],
    [
        'slug' => 'resources-news',
        'title' => 'News & Insights',
        'excerpt' => 'News and insights from ASNEN programmes, events, and the wider network.',
        'body' => '<p>Articles and updates from across the ASNEN network. Individual articles are managed under Articles in admin.</p>',
    ],
    [
        'slug' => 'gallery',
        'title' => 'Gallery',
        'excerpt' => 'Photographs from ASNEN programmes and events, published with consent and descriptive metadata.',
        'body' => '<p>Gallery albums appear here as media assets are uploaded and approved for public display. Albums are managed under Galleries in admin.</p>',
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
