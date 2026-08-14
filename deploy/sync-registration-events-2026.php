<?php

/**
 * Publish the Nov-Dec 2026 NCPWD webinar and Disability Registration Day.
 * Idempotent. Safe to re-run on deploy.
 */

use App\Enums\PublishStatus;
use App\Enums\VerificationStatus;
use App\Models\Event;
use App\Models\ImpactStory;
use App\Models\MediaAsset;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$komolionImageId = ImpactStory::query()
    ->where('slug', ImpactStory::KOMOLION_SLUG)
    ->value('featured_image_id');

$webinarImageId = Event::query()
    ->where('slug', 'improving-our-support-system')
    ->value('featured_image_id')
    ?: MediaAsset::query()->where('path', 'events/improving-support-system.jpg')->value('id');

$events = [
    [
        'title' => 'Why Registration Matters: A Conversation with NCPWD Leadership',
        'slug' => 'why-registration-matters-ncpwd',
        'type' => 'webinar',
        'summary' => 'An online conversation with NCPWD leadership on why disability registration matters, ahead of ASNEN\'s 5 December 2026 Disability Registration Day.',
        'body' => '<p>Ahead of our Disability Registration Day on 5 December 2026, join ASNEN and leadership from the National Council for Persons with Disabilities (NCPWD) for an open conversation on why registration matters: what it unlocks for persons with disabilities and their caregivers, and why so many eligible families in Kenya remain unregistered.</p>',
        'venue' => 'Online',
        'is_online' => true,
        'starts_at' => '2026-11-23 19:00:00',
        'ends_at' => '2026-11-23 20:30:00',
        'featured_image_id' => $webinarImageId,
    ],
    [
        'title' => 'Disability Registration Day',
        'slug' => 'disability-registration-day-2026',
        'type' => 'outreach',
        'summary' => 'A day-long registration and medical assessment camp for children, caregivers and adults with disabilities across six Nairobi wards, delivered with NCPWD and the Ministry of Health.',
        'body' => '<p>On 5 December 2026, ASNEN, together with the National Council for Persons with Disabilities (NCPWD) and the Ministry of Health, is holding a day-long registration and medical assessment camp. The day is open to children with disabilities, their caregivers, and any person with a disability across Kikuyu, Thogoto, Dagoretti, Kawangware, Uthiru and Waithaka wards.</p>',
        'venue' => 'Acorn Special Tutorials, Dagoretti South',
        'is_online' => false,
        'starts_at' => '2026-12-05 08:00:00',
        'ends_at' => '2026-12-05 16:00:00',
        'featured_image_id' => $komolionImageId,
    ],
];

foreach ($events as $event) {
    $model = Event::updateOrCreate(
        ['slug' => $event['slug']],
        [
            'title' => $event['title'],
            'type' => $event['type'],
            'summary' => $event['summary'],
            'body' => $event['body'],
            'venue' => $event['venue'],
            'is_online' => $event['is_online'],
            'starts_at' => $event['starts_at'],
            'ends_at' => $event['ends_at'],
            'timezone' => 'Africa/Nairobi',
            'featured_image_id' => $event['featured_image_id'],
            'status' => PublishStatus::Published,
            'published_at' => now(),
            'verification_status' => VerificationStatus::Verified,
        ]
    );

    echo "synced {$model->slug} id={$model->id}\n";
}
