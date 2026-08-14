<?php

/**
 * Import unique images from https://acorn.co.ke/gallery into a published ASNEN gallery.
 * Skips files whose content hash already exists in media_assets.
 */

use App\Enums\ConsentStatus;
use App\Enums\PublishStatus;
use App\Enums\SafeguardingStatus;
use App\Enums\VerificationStatus;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\MediaAsset;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$urlFile = __DIR__.'/acorn-gallery-urls.txt';
if (! File::exists($urlFile)) {
    throw new RuntimeException('Missing URL list: '.$urlFile);
}

$urls = collect(preg_split('/\R/', File::get($urlFile)))
    ->map(fn ($line) => trim($line))
    ->filter(fn ($line) => str_starts_with($line, 'https://acorn.co.ke/storage/gallery/'))
    ->unique()
    ->values();

$gallery = Gallery::query()
    ->whereIn('slug', ['general-gallery', 'acorn-special-tutorials'])
    ->first();

$attrs = [
    'title' => 'General Gallery',
    'description' => 'Photographs from ASNEN programmes, trainings, and events.',
    'location' => 'Nairobi, Kenya',
    'status' => PublishStatus::Published,
    'published_at' => $gallery?->published_at ?? now(),
];

if (Schema::hasColumn('galleries', 'verification_status')) {
    $attrs['verification_status'] = VerificationStatus::Verified;
}
if (Schema::hasColumn('galleries', 'requires_safeguarding')) {
    $attrs['requires_safeguarding'] = false;
}
if (Schema::hasColumn('galleries', 'safeguarding_status')) {
    $attrs['safeguarding_status'] = SafeguardingStatus::NotRequired;
}

if ($gallery) {
    $gallery->fill($attrs);
    $gallery->slug = 'general-gallery';
    $gallery->saveQuietly();
} else {
    $gallery = Gallery::create(array_merge($attrs, ['slug' => 'general-gallery']));
}

$folder = 'galleries/acorn';
$destDir = storage_path('app/public/'.$folder);
File::ensureDirectoryExists($destDir);

$hasHash = Schema::hasColumn('media_assets', 'content_hash');
$maxSort = (int) ($gallery->items()->max('sort_order') ?? 0);
$added = 0;
$reused = 0;
$skipped = 0;
$failed = 0;

$context = stream_context_create([
    'http' => [
        'timeout' => 60,
        'header' => "User-Agent: ASNEN gallery import\r\n",
    ],
    'ssl' => [
        'verify_peer' => true,
        'verify_peer_name' => true,
    ],
]);

foreach ($urls as $index => $url) {
    $basename = basename(parse_url($url, PHP_URL_PATH) ?: '');
    if ($basename === '' || ! preg_match('/\.(jpe?g|png|webp)$/i', $basename)) {
        $skipped++;
        continue;
    }

    $tmp = sys_get_temp_dir().'/acorn-'.$basename;
    $bytes = @file_get_contents($url, false, $context);
    if ($bytes === false || $bytes === '') {
        $failed++;
        echo "FAIL download {$url}\n";
        continue;
    }

    File::put($tmp, $bytes);
    $hash = hash_file('sha256', $tmp);

    $existing = $hasHash
        ? MediaAsset::query()->where('content_hash', $hash)->first()
        : MediaAsset::query()->where('filename', $basename)->where('folder', 'galleries/acorn')->first();

    if ($existing) {
        File::delete($tmp);
        $item = GalleryItem::firstOrCreate(
            [
                'gallery_id' => $gallery->id,
                'media_asset_id' => $existing->id,
            ],
            [
                'caption' => null,
                'sort_order' => ++$maxSort,
            ]
        );
        $reused++;
        echo "SKIP existing hash {$basename} media={$existing->id} item={$item->id}\n";
        continue;
    }

    $relative = $folder.'/'.$basename;
    $dest = storage_path('app/public/'.$relative);
    File::copy($tmp, $dest);
    File::delete($tmp);

    $payload = [
        'filename' => $basename,
        'mime' => File::mimeType($dest) ?: 'image/jpeg',
        'size' => File::size($dest),
        'alt' => 'Acorn Special Tutorials gallery photo',
        'folder' => $folder,
        'is_private' => false,
        'consent_status' => ConsentStatus::NotRequired,
        'credit' => 'Acorn Special Tutorials (acorn.co.ke/gallery)',
    ];
    if ($hasHash) {
        $payload['content_hash'] = $hash;
    }

    $media = MediaAsset::updateOrCreate(
        ['path' => $relative, 'disk' => 'public'],
        $payload
    );

    GalleryItem::firstOrCreate(
        [
            'gallery_id' => $gallery->id,
            'media_asset_id' => $media->id,
        ],
        [
            'caption' => null,
            'sort_order' => ++$maxSort,
        ]
    );

    $added++;
    echo 'ADD '.($index + 1).'/'.$urls->count()." {$basename} media={$media->id}\n";
}

echo "DONE gallery={$gallery->id} added={$added} reused={$reused} skipped={$skipped} failed={$failed} items=".$gallery->items()->count()."\n";
