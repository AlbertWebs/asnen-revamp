<?php

use App\Enums\PublishStatus;
use App\Enums\VerificationStatus;
use App\Models\Event;
use App\Models\MediaAsset;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$imageId = MediaAsset::query()
    ->where('path', 'team/eva-naputuni.jpg')
    ->value('id')
    ?: Event::query()->where('slug', '2nd-ubuntu-conference')->value('featured_image_id');

$body = <<<'HTML'
<p>Our Founder, Eva Naputuni Nyoike (OGW), participated in the Technical Validation Workshop of the African Continental Curriculum Framework in Windhoek, Namibia.</p>
<p>At this important continental platform, Eva advocated for something we believe is fundamental: a curriculum that sees every child, values every child, and leaves no child behind.</p>
<p>She called for Ubuntu, "I am because you are", to be embraced as a guiding philosophy for the African Continental Curriculum Framework. A curriculum rooted in Ubuntu recognises that education is not only about academic achievement; it is about dignity, belonging, community, and ensuring that every learner has an opportunity to thrive.</p>
<p>She also highlighted the critical importance of early identification and early intervention, particularly for children with disabilities and developmental differences. Too many children across Africa are identified only after years of struggle, exclusion, or missed opportunities.</p>
<p>And Eva is determined to raise the conversation that is still too often missing: DISABILITY.</p>
<p>If Africa is building a curriculum for its children, then children with disabilities must not be an afterthought. They must be visible from the beginning. They must be part of the design, the language, the systems, and the future.</p>
<p>This is the mark we want to leave.</p>
<p>Not just a curriculum for Africa, but a curriculum that truly sees Africa's children.</p>
<p>Ubuntu. Inclusion. Early intervention. Equity. Dignity.</p>
<p>Because when we say every child, we mean every child.</p>
HTML;

$event = Event::updateOrCreate(
    ['slug' => 'leaving-a-mark-where-it-matters'],
    [
        'title' => 'Leaving a mark where it matters',
        'type' => 'workshop',
        'summary' => 'Our Founder, Eva Naputuni Nyoike (OGW), participated in the Technical Validation Workshop of the African Continental Curriculum Framework in Windhoek, Namibia.',
        'body' => $body,
        'venue' => 'Windhoek, Namibia',
        'is_online' => false,
        'starts_at' => '2026-08-11 08:00:00',
        'ends_at' => '2026-08-22 18:00:00',
        'timezone' => 'Africa/Nairobi',
        'featured_image_id' => $imageId,
        'status' => PublishStatus::Published,
        'published_at' => now(),
        'verification_status' => VerificationStatus::Verified,
    ]
);

echo "synced {$event->slug} id={$event->id} image={$imageId}\n";
