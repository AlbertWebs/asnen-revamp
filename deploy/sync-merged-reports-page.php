<?php

use App\Models\NavigationItem;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$updated = NavigationItem::query()
    ->where('url', '/impact/reports')
    ->update(['url' => '/resources/publications']);

echo "nav_updated={$updated}\n";
