@props([
    'asset' => null,
    'alt' => null,
    'ratio' => '16/9',
    'rounded' => 'rounded-lg',
    'label' => 'Image coming soon',
    'class' => '',
    'fit' => 'cover',
    'fill' => false,
])

@php
    $url = is_object($asset) && method_exists($asset, 'publicUrl') ? $asset->publicUrl() : null;
    $resolvedAlt = $alt ?? (is_object($asset) ? ($asset->alt ?? '') : '');
    $fitClass = $fit === 'contain' ? 'object-contain' : 'object-cover';
@endphp

@if($fill)
    @if($url)
        <div class="relative h-full min-h-[14rem] overflow-hidden bg-sand {{ $rounded }} {{ $class }}">
            <img
                src="{{ $url }}"
                alt="{{ $resolvedAlt }}"
                class="absolute inset-0 h-full w-full {{ $fitClass }}"
                loading="lazy"
                decoding="async"
            >
        </div>
    @else
        <div
            class="relative flex h-full min-h-[14rem] flex-col items-center justify-center gap-2 border border-dashed border-brand/30 bg-gradient-to-br from-brand-50 via-sand to-lime-50 text-center {{ $rounded }} {{ $class }}"
            role="img"
            aria-label="{{ $label }}"
        >
            <svg class="h-10 w-10 text-brand/40" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                <circle cx="16" cy="16" r="8" stroke="currentColor" stroke-width="2"/>
                <circle cx="30" cy="14" r="6" stroke="currentColor" stroke-width="2"/>
                <circle cx="34" cy="28" r="8" stroke="currentColor" stroke-width="2"/>
                <circle cx="18" cy="32" r="5" stroke="currentColor" stroke-width="2"/>
            </svg>
            <span class="px-3 font-mono text-[0.65rem] uppercase tracking-wider text-brand/70">{{ $label }}</span>
        </div>
    @endif
@elseif($url)
    <div class="overflow-hidden bg-sand {{ $rounded }} {{ $class }}" style="aspect-ratio: {{ $ratio }};">
        <img
            src="{{ $url }}"
            alt="{{ $resolvedAlt }}"
            class="h-full w-full {{ $fitClass }}"
            loading="lazy"
            decoding="async"
        >
    </div>
@else
    <div
        class="flex flex-col items-center justify-center gap-2 border border-dashed border-brand/30 bg-gradient-to-br from-brand-50 via-sand to-lime-50 text-center {{ $rounded }} {{ $class }}"
        style="aspect-ratio: {{ $ratio }};"
        role="img"
        aria-label="{{ $label }}"
    >
        <svg class="h-10 w-10 text-brand/40" viewBox="0 0 48 48" fill="none" aria-hidden="true">
            <circle cx="16" cy="16" r="8" stroke="currentColor" stroke-width="2"/>
            <circle cx="30" cy="14" r="6" stroke="currentColor" stroke-width="2"/>
            <circle cx="34" cy="28" r="8" stroke="currentColor" stroke-width="2"/>
            <circle cx="18" cy="32" r="5" stroke="currentColor" stroke-width="2"/>
        </svg>
        <span class="px-3 font-mono text-[0.65rem] uppercase tracking-wider text-brand/70">{{ $label }}</span>
    </div>
@endif
