<?php

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== storage permissions ===\n";
$root = storage_path('app/public');
passthru('ls -la '.escapeshellarg($root));
passthru('namei -l '.escapeshellarg(public_path('storage')));

echo "\n=== broken path rows ===\n";
$broken = MediaAsset::withTrashed()
    ->where(function ($q) {
        $q->where('path', '0')
            ->orWhere('path', '')
            ->orWhereNull('path');
    })
    ->get(['id', 'filename', 'path', 'folder', 'created_at']);
echo 'count='.$broken->count().PHP_EOL;
foreach ($broken->take(15) as $row) {
    echo "#{$row->id} {$row->filename} path={$row->path} folder={$row->folder}\n";
}

echo "\n=== writable check ===\n";
$testDir = $root.'/uploads';
if (! is_dir($testDir)) {
    @mkdir($testDir, 0775, true);
}
$probe = $testDir.'/.write_probe_'.getmypid();
$ok = @file_put_contents($probe, 'ok') !== false;
echo 'uploads writable: '.($ok ? 'YES' : 'NO').PHP_EOL;
if ($ok) {
    @unlink($probe);
}
