@props([
    'publication',
    'href' => null,
    'label' => 'Publication cover',
])

@php
    $href ??= route('site.resources.publications.show', $publication->slug);
    $url = $publication->cover?->publicUrl();
    $alt = $publication->cover?->alt ?: $publication->title;
    $accent = match ($publication->category) {
        'toolkit' => '#8CC63F',
        'guide' => '#FFF200',
        'conference_report' => '#0C77BC',
        'impact_report', 'annual_report' => '#4A4C70',
        default => '#0C77BC',
    };
    $textOnAccent = $publication->category === 'guide' ? '#20212B' : '#fff';
@endphp

<a
    href="{{ $href }}"
    class="report-card__thumb"
    style="--thumb-accent: {{ $accent }}; --thumb-ink: {{ $textOnAccent }};"
    aria-label="{{ $label }}: {{ $publication->title }}"
>
    @if($url)
        <img
            src="{{ $url }}"
            alt="{{ $alt }}"
            class="report-card__thumb-img"
            loading="lazy"
            decoding="async"
            width="320"
            height="427"
        >
    @else
        <span class="report-card__thumb-fallback" aria-hidden="true">
            <span class="report-card__thumb-cat">{{ $publication->categoryLabel() }}</span>
            <span class="report-card__thumb-title">{{ $publication->title }}</span>
            @if($publication->year)
                <span class="report-card__thumb-year">{{ $publication->year }}</span>
            @endif
        </span>
    @endif
</a>
