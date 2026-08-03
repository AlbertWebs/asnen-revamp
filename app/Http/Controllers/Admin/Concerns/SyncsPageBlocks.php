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
            if (! is_array($content)) {
                $content = [];
            }

            if (array_key_exists('image_id', $content) && ($content['image_id'] === '' || $content['image_id'] === 'null' || $content['image_id'] === false)) {
                $content['image_id'] = null;
            } elseif (! empty($content['image_id'])) {
                $content['image_id'] = (int) $content['image_id'];
            }

            if (array_key_exists('image_ids', $content)) {
                $content['image_ids'] = collect(is_array($content['image_ids']) ? $content['image_ids'] : [])
                    ->filter(fn ($id) => filled($id) && $id !== 'null' && $id !== false)
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->all();

                if ($content['image_ids'] !== [] && empty($content['image_id'])) {
                    $content['image_id'] = $content['image_ids'][0];
                }
            }

            // Keep CTA payloads shaped for the public hero component.
            if (($block['type'] ?? '') === 'hero') {
                if (! isset($content['image_ids']) || ! is_array($content['image_ids'])) {
                    $content['image_ids'] = ! empty($content['image_id']) ? [(int) $content['image_id']] : [];
                }

                foreach (['primary_cta', 'secondary_cta'] as $ctaKey) {
                    if (! isset($content[$ctaKey]) || ! is_array($content[$ctaKey])) {
                        $content[$ctaKey] = ['label' => '', 'url' => ''];
                    } else {
                        $content[$ctaKey] = [
                            'label' => (string) ($content[$ctaKey]['label'] ?? ''),
                            'url' => (string) ($content[$ctaKey]['url'] ?? ''),
                        ];
                    }
                }
            }

            $page->blocks()->create([
                'type' => $block['type'],
                'sort_order' => $index,
                'is_visible' => filter_var($block['is_visible'] ?? true, FILTER_VALIDATE_BOOLEAN),
                'content' => $content,
                'settings' => $block['settings'] ?? null,
                'anchor_id' => $block['anchor_id'] ?? Str::slug($block['type'].'-'.$index),
            ]);
        }
    }
}
