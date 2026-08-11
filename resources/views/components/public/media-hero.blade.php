@props([
    'title',
    'titleMax' => '14ch',
    'eyebrow' => null,
    'excerpt' => null,
    'bodyHtml' => null,
    'headingId' => 'media-hero-heading',
    'currentLabel' => null,
    'parentLabel' => 'Get Involved',
    'parentUrl' => null,
    'primaryCta' => null,
    'secondaryCta' => null,
    'images' => [],
    'fallbackImage' => 'storage/galleries/community-moments/03.jpg',
])

@php
    $heroImage = collect($images)->first();
    $heroImageUrl = $heroImage
        ? (is_object($heroImage) ? $heroImage->publicUrl() : ($heroImage['url'] ?? $heroImage['src'] ?? null))
        : asset($fallbackImage);
    $parentHref = $parentUrl ?? route('site.get-involved.index');
@endphp

<section {{ $attributes->class(['impact-hero', 'impact-hero--media']) }} aria-labelledby="{{ $headingId }}">
    <div class="impact-hero__media" aria-hidden="true">
        <img
            src="{{ $heroImageUrl }}"
            alt=""
            class="impact-hero__photo"
            width="1600"
            height="900"
            loading="eager"
            fetchpriority="high"
            decoding="async"
        >
    </div>
    <div class="impact-hero__veil" aria-hidden="true"></div>

    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="impact-hero__inner reveal">
            <nav aria-label="Breadcrumb" class="impact-hero__crumbs">
                <ol>
                    <li><a href="{{ route('site.home') }}">Home</a></li>
                    @if($currentLabel)
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ $parentHref }}">{{ $parentLabel }}</a></li>
                        <li aria-hidden="true">/</li>
                        <li><span aria-current="page">{{ $currentLabel }}</span></li>
                    @else
                        <li aria-hidden="true">/</li>
                        <li><span aria-current="page">{{ $parentLabel }}</span></li>
                    @endif
                </ol>
            </nav>

            @if($eyebrow)
                <span class="impact-hero__eyebrow">{{ $eyebrow }}</span>
            @endif

            <h1 id="{{ $headingId }}" class="impact-hero__title" style="max-width: {{ $titleMax }};">{{ $title }}</h1>

            @if($excerpt)
                <p class="impact-hero__excerpt">{{ $excerpt }}</p>
            @endif

            @if($bodyHtml)
                <div class="impact-hero__body">
                    <x-public.prose :html="$bodyHtml" />
                </div>
            @endif

            @if($slot->isNotEmpty())
                <div class="impact-hero__body">
                    {{ $slot }}
                </div>
            @endif

            @if($primaryCta || $secondaryCta)
                <div class="impact-hero__actions">
                    @if($primaryCta)
                        <a href="{{ $primaryCta['url'] }}" class="btn-gold">{{ $primaryCta['label'] }}</a>
                    @endif
                    @if($secondaryCta)
                        <a href="{{ $secondaryCta['url'] }}" class="impact-hero__ghost">{{ $secondaryCta['label'] }}</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
