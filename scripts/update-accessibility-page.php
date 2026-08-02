<?php

use App\Models\Page;
use App\Models\PageBlock;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$page = Page::where('slug', 'accessibility')->first();
if (! $page) {
    echo "No accessibility page\n";
    exit(0);
}

$block = PageBlock::where('page_id', $page->id)->where('type', 'rich_text')->first();
$body = <<<'HTML'
<p>ASNEN is committed to an inclusive web experience that conforms to <strong>WCAG 2.2 Level AA</strong>. Accessibility is part of our Ubuntu values: dignity, belonging, and honest accounting.</p>
<p>Use the <strong>Accessibility</strong> button (bottom left) or press <kbd>Alt</kbd>+<kbd>0</kbd> to change text size, contrast, motion, fonts, and more. Preferences apply across the whole website and are saved on this device.</p>
<p>If you find a barrier, email <a href="mailto:info@asnenafrica.org">info@asnenafrica.org</a> or use our contact form.</p>
HTML;

if ($block) {
    $content = $block->content;
    $content['body'] = $body;
    $block->update(['content' => $content]);
    echo "Updated rich_text block\n";
} else {
    PageBlock::create([
        'page_id' => $page->id,
        'type' => 'rich_text',
        'sort_order' => 1,
        'is_visible' => true,
        'content' => ['body' => $body],
        'settings' => [],
    ]);
    echo "Created rich_text block\n";
}
