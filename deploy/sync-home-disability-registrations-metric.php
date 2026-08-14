<?php

use App\Enums\PublishStatus;
use App\Enums\VerificationStatus;
use App\Models\ImpactMetric;
use App\Models\PageBlock;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$metric = ImpactMetric::query()->where('label', 'Disability registration and medical camps')->first()
    ?? ImpactMetric::query()->where('label', 'Disability registrations / medical camp')->first();

$payload = [
    'label' => 'Disability registration and medical camps',
    'value' => '5',
    'numeric_value' => 5,
    'public_label' => '5',
    'source_label' => 'asnenafrica.org',
    'verification_status' => VerificationStatus::Verified,
    'status' => PublishStatus::Published,
    'published_at' => now(),
];

if ($metric) {
    $metric->update($payload);
} else {
    $metric = ImpactMetric::create($payload);
}

ImpactMetric::query()
    ->where('label', 'Disability registrations / medical camp')
    ->where('id', '!=', $metric->id)
    ->update([
        'status' => PublishStatus::Draft,
        'published_at' => null,
    ]);

$block = PageBlock::query()
    ->whereHas('page', fn ($q) => $q->where('slug', 'home'))
    ->where('type', 'statistics')
    ->first();

if (! $block) {
    fwrite(STDERR, "Home statistics block not found.\n");
    exit(1);
}

$content = $block->content ?? [];
$ids = collect($content['metric_ids'] ?? [])
    ->map(fn ($id) => (int) $id)
    ->filter()
    ->values()
    ->all();

if (! in_array((int) $metric->id, $ids, true)) {
    $ids[] = (int) $metric->id;
}

$content['heading'] = $content['heading'] ?? 'Impact at a Glance';
$content['metric_ids'] = $ids;
$block->content = $content;
$block->save();

echo "Metric #{$metric->id}: {$metric->value} {$metric->label}\n";
echo 'Home statistics metric_ids: '.json_encode($ids)."\n";
