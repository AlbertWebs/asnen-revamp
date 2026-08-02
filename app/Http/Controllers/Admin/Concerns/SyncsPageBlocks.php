<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Page;
use Illuminate\Support\Str;

trait SyncsPageBlocks
{
    protected function syncPageBlocks(Page $page, array $blocks): void
    {
        $page->blocks()->delete();

        foreach ($blocks as $index => $block) {
            if (! is_array($block) || empty($block['type'])) {
                continue;
            }

            $content = $block['content'] ?? [];
            if (array_key_exists('image_id', $content) && ($content['image_id'] === '' || $content['image_id'] === 'null')) {
                $content['image_id'] = null;
            } elseif (! empty($content['image_id'])) {
                $content['image_id'] = (int) $content['image_id'];
            }

            $page->blocks()->create([
                'type' => $block['type'],
                'sort_order' => $index,
                'is_visible' => (bool) ($block['is_visible'] ?? true),
                'content' => $content,
                'settings' => $block['settings'] ?? null,
                'anchor_id' => $block['anchor_id'] ?? Str::slug($block['type'].'-'.$index),
            ]);
        }
    }
}
