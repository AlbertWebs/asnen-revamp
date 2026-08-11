<?php

/**
 * Idempotent sync of ASNEN Annual Report 2024 into Impact Reports.
 */

use App\Enums\ConsentStatus;
use App\Enums\PublishStatus;
use App\Enums\VerificationStatus;
use App\Models\MediaAsset;
use App\Models\Publication;
use Illuminate\Support\Facades\File;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$relative = 'resources/ASNEN-Annual-Report-2024.docx';
$absolute = storage_path('app/public/'.$relative);

$publicSource = public_path('ASNEN-Annual-Report-2024.docx');
if (! File::exists($absolute) && File::exists($publicSource)) {
    File::ensureDirectoryExists(dirname($absolute));
    File::copy($publicSource, $absolute);
}

if (! File::exists($absolute)) {
    echo "FILE_MISSING {$absolute}\n";
    exit(1);
}

$file = MediaAsset::updateOrCreate(
    ['path' => $relative, 'disk' => 'public'],
    [
        'filename' => 'ASNEN-Annual-Report-2024.docx',
        'mime' => File::mimeType($absolute) ?: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'size' => File::size($absolute),
        'alt' => 'ASNEN Annual Report 2024',
        'folder' => 'resources',
        'is_private' => false,
        'consent_status' => ConsentStatus::NotRequired,
        'credit' => 'Africa Special Needs Education Network',
    ]
);

$publication = Publication::updateOrCreate(
    ['slug' => 'asnen-annual-report-2024'],
    [
        'title' => 'ASNEN Annual Report 2024',
        'category' => 'annual_report',
        'year' => 2024,
        'abstract' => 'ASNEN\'s 2024 annual report on inclusive education programmes, outreach, partnerships, and impact across the network.',
        'file_id' => $file->id,
        'status' => PublishStatus::Published,
        'published_at' => now(),
        'verification_status' => VerificationStatus::Verified,
    ]
);

echo "OK publication_id={$publication->id} file_id={$file->id} slug={$publication->slug}\n";
