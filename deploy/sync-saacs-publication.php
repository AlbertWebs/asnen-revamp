<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Enums\ConsentStatus;
use App\Enums\PublishStatus;
use App\Enums\VerificationStatus;
use App\Models\MediaAsset;
use App\Models\Publication;
use Illuminate\Support\Facades\File;

$relative = 'resources/saacs-asnen.pdf';
$absolute = storage_path('app/public/'.$relative);

if (! File::exists($absolute)) {
    echo "FILE_MISSING {$absolute}\n";
    exit(1);
}

$file = MediaAsset::updateOrCreate(
    ['path' => $relative, 'disk' => 'public'],
    [
        'filename' => 'SAACS_ASNEN.pptx.pdf',
        'mime' => File::mimeType($absolute) ?: 'application/pdf',
        'size' => File::size($absolute),
        'alt' => 'SAACS ASNEN AAC presentation',
        'folder' => 'resources',
        'is_private' => false,
        'consent_status' => ConsentStatus::NotRequired,
        'credit' => 'Africa Special Needs Education Network',
    ]
);
echo "file_id={$file->id}\n";

$coverPath = 'events/aac-communication.jpg';
$cover = MediaAsset::query()->where('path', $coverPath)->where('disk', 'public')->first();
if (! $cover && File::exists(storage_path('app/public/'.$coverPath))) {
    $coverAbs = storage_path('app/public/'.$coverPath);
    $cover = MediaAsset::updateOrCreate(
        ['path' => $coverPath, 'disk' => 'public'],
        [
            'filename' => basename($coverPath),
            'mime' => File::mimeType($coverAbs) ?: 'image/jpeg',
            'size' => File::size($coverAbs),
            'alt' => 'AAC webinar cover',
            'folder' => 'events',
            'is_private' => false,
            'consent_status' => ConsentStatus::NotRequired,
        ]
    );
}

$publication = Publication::updateOrCreate(
    ['slug' => 'saacs-asnen-aac'],
    [
        'title' => 'SAACS ASNEN: Alternative and Augmentative Communication (AAC)',
        'category' => 'report',
        'year' => 2026,
        'abstract' => 'Presentation materials from the ASNEN webinar on Alternative and Augmentative Communication (AAC).',
        'file_id' => $file->id,
        'cover_id' => $cover?->id,
        'status' => PublishStatus::Published,
        'published_at' => now(),
        'verification_status' => VerificationStatus::Verified,
    ]
);

echo "publication_id={$publication->id} slug={$publication->slug}\n";
echo "DONE\n";
