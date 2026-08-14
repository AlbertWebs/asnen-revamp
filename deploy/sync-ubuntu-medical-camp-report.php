<?php

use App\Enums\ConsentStatus;
use App\Enums\PublishStatus;
use App\Enums\VerificationStatus;
use App\Models\MediaAsset;
use App\Models\Publication;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pdfSource = public_path('mediacalcamp/Ubuntu Medical Camp Report_260814_134249.pdf');
$coverSource = public_path('mediacalcamp/banners.jpeg');

$files = [
    'pdf' => [
        'path' => 'resources/ubuntu-medical-camp-report.pdf',
        'source' => $pdfSource,
        'alt' => 'Ubuntu Medical Camp Report',
        'mime' => 'application/pdf',
    ],
    'cover' => [
        'path' => 'resources/ubuntu-medical-camp-cover.jpeg',
        'source' => $coverSource,
        'alt' => 'Ubuntu Medical Camp and NCPWD registration, Embakasi',
        'mime' => 'image/jpeg',
    ],
];

$mediaIds = [];

foreach ($files as $key => $file) {
    $dest = storage_path('app/public/'.$file['path']);
    File::ensureDirectoryExists(dirname($dest));

    if (File::exists($file['source'])) {
        File::copy($file['source'], $dest);
    }

    if (! File::exists($dest)) {
        throw new RuntimeException('File missing: '.$file['path']);
    }

    $payload = [
        'filename' => basename($file['path']),
        'mime' => File::mimeType($dest) ?: $file['mime'],
        'size' => File::size($dest),
        'alt' => $file['alt'],
        'folder' => 'resources',
        'is_private' => false,
        'consent_status' => ConsentStatus::NotRequired,
        'credit' => 'Africa Special Needs Education Network',
    ];

    if (Schema::hasColumn('media_assets', 'content_hash')) {
        $payload['content_hash'] = hash_file('sha256', $dest);
    }

    $media = MediaAsset::updateOrCreate(
        ['path' => $file['path'], 'disk' => 'public'],
        $payload
    );

    $mediaIds[$key] = $media->id;
}

$publication = Publication::updateOrCreate(
    ['slug' => 'ubuntu-medical-camp-report'],
    [
        'title' => 'Ubuntu Medical Camp Report',
        'category' => 'impact_report',
        'year' => 2026,
        'abstract' => 'Report from the Ubuntu medical camp and NCPWD registration at St. Nicholas Junior Academy, Embakasi, covering assessment, registration, and partner collaboration.',
        'file_id' => $mediaIds['pdf'],
        'cover_id' => $mediaIds['cover'],
        'status' => PublishStatus::Published,
        'published_at' => now(),
        'verification_status' => VerificationStatus::Verified,
    ]
);

echo "synced {$publication->slug} id={$publication->id} file={$mediaIds['pdf']} cover={$mediaIds['cover']}\n";
