<?php

use App\Models\Gallery;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$gallery = Gallery::query()
    ->whereIn('slug', ['general-gallery', 'acorn-special-tutorials'])
    ->orderByRaw("CASE WHEN slug = 'general-gallery' THEN 0 ELSE 1 END")
    ->first();

if (! $gallery) {
    echo "gallery not found\n";
    exit(1);
}

$updates = [
    'title' => 'General Gallery',
    'slug' => 'general-gallery',
    'description' => 'Photographs from ASNEN programmes, trainings, and events.',
];

if (Schema::hasColumn('galleries', 'status')) {
    $updates['status'] = $gallery->status?->value ?? $gallery->getAttributes()['status'] ?? 'published';
}

Gallery::query()->whereKey($gallery->id)->update($updates);
$gallery->refresh();

echo "id={$gallery->id} slug={$gallery->slug} title={$gallery->title} items={$gallery->items()->count()}\n";
