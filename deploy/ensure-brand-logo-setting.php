<?php

use App\Models\Setting;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Setting::query()->firstOrCreate(
    ['key' => 'brand.logo_id'],
    [
        'group' => 'brand',
        'value' => ['value' => ''],
        'is_public' => true,
    ]
);

echo "brand.logo_id ready\n";
