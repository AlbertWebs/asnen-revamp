<?php

use App\Models\PageBlock;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$block = PageBlock::query()
    ->whereHas('page', fn ($q) => $q->where('slug', 'home'))
    ->where('type', 'partners')
    ->first();

if (! $block) {
    fwrite(STDERR, "Home partners block not found.\n");
    exit(1);
}

$content = $block->content ?? [];
$content['heading'] = 'Our Collaborators';
$block->content = $content;
$block->save();

echo "Updated home partners heading to: Our Collaborators\n";
