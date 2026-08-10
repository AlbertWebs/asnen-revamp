<?php

use App\Models\Page;
use App\Models\PageBlock;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$page = Page::where('slug', 'home')->first();
if (! $page) {
    fwrite(STDERR, "No home page\n");
    exit(1);
}

$hero = PageBlock::where('page_id', $page->id)->where('type', 'hero')->first();
if ($hero) {
    $content = $hero->content;
    $content['headline'] = 'Inclusion for all, in all. No child left behind.';
    $content['supporting_text'] = 'ASNEN is a coalition of families, educators and advocates across Africa, building a model of inclusion rooted in Ubuntu, carried by the people who live it, not delivered to them.';
    $content['primary_cta'] = ['label' => 'Become a member', 'url' => '/get-involved/membership'];
    $content['secondary_cta'] = ['label' => 'Explore our programs', 'url' => '/what-we-do'];
    $hero->update(['content' => $content]);
    echo "Hero updated\n";
}

if (! PageBlock::where('page_id', $page->id)->where('type', 'ubuntu_values')->exists()) {
    $who = PageBlock::where('page_id', $page->id)->where('type', 'who_we_are')->first();
    $sort = $who ? ((int) $who->sort_order + 1) : 3;
    PageBlock::where('page_id', $page->id)->where('sort_order', '>=', $sort)->increment('sort_order');
    PageBlock::create([
        'page_id' => $page->id,
        'type' => 'ubuntu_values',
        'sort_order' => $sort,
        'is_visible' => true,
        'content' => [
            'eyebrow' => 'Our values',
            'heading' => 'Written as behaviours, not aspirations - so members and partners can hold us to them.',
        ],
        'settings' => [],
    ]);
    echo "Ubuntu values block added\n";
}

$whoBlock = PageBlock::where('page_id', $page->id)->where('type', 'who_we_are')->first();
if ($whoBlock) {
    $c = $whoBlock->content;
    $c['heading'] = 'An Africa where inclusion is woven into the fabric of society.';
    $whoBlock->update(['content' => $c]);
    echo "Who we are updated\n";
}

$programs = PageBlock::where('page_id', $page->id)->where('type', 'program_grid')->first();
if ($programs) {
    $c = $programs->content;
    $c['heading'] = 'We believe we can reach more families with you.';
    $programs->update(['content' => $c]);
    echo "Programs updated\n";
}

echo "Done\n";
