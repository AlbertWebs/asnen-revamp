@props(['blocks', 'sanitizer' => null])

@php
    $sanitizer = $sanitizer ?? app(\App\Services\HtmlSanitizer::class);
@endphp

@foreach($blocks as $block)
    @if($block->is_visible)
        @includeFirst([
            'components.public.blocks.'.$block->type,
            'components.public.blocks.fallback',
        ], ['block' => $block, 'content' => $block->content ?? [], 'sanitizer' => $sanitizer])
    @endif
@endforeach
