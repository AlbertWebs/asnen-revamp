<?php

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$assets = MediaAsset::query()
    ->whereNull('content_hash')
    ->whereNotNull('path')
    ->where('path', '!=', '0')
    ->where('path', '!=', '')
    ->get();

$updated = 0;
$missing = 0;

foreach ($assets as $asset) {
    try {
        $full = Storage::disk($asset->disk)->path($asset->path);
    } catch (Throwable $e) {
        $missing++;
        continue;
    }

    if (! is_file($full)) {
        $missing++;
        continue;
    }

    $hash = hash_file('sha256', $full);
    if (! $hash) {
        $missing++;
        continue;
    }

    $asset->forceFill(['content_hash' => $hash])->save();
    $updated++;
}

echo "hashed={$updated} missing_file={$missing}\n";
