@props([
    'title',
    'titleMax' => '14ch',
    'eyebrow' => null,
    'tagline' => null,
    'excerpt' => null,
    'bodyHtml' => null,
    'headingId' => 'media-hero-heading',
    'currentLabel' => null,
    'parentLabel' => 'Get Involved',
    'parentUrl' => null,
    'showParent' => true,
    'primaryCta' => null,
    'secondaryCta' => null,
    'images' => [],
    'fallbackImage' => 'storage/galleries/community-moments/03.jpg',
])

@php
    $slides = collect($images)
        ->map(function ($image) {
            if (! $image) {
                return null;
            }
            if (is_string($image)) {
                return ['url' => $image, 'alt' => ''];
            }
            $url = is_object($image) ? $image->publicUrl() : ($image['url'] ?? $image['src'] ?? null);
            if (! $url) {
                return null;
            }

            return [
                'url' => $url,
                'alt' => is_object($image)
                    ? ($image->alt ?? '')
                    : ($image['alt'] ?? ''),
            ];
        })
        ->filter()
        ->values();

    if ($slides->isEmpty()) {
        $slides = collect([
            ['url' => asset($fallbackImage), 'alt' => ''],
        ]);
    }

    $slideCount = $slides->count();
    $parentHref = $parentUrl ?? route('site.get-involved.index');
@endphp

<section
    {{ $attributes->class(['impact-hero', 'impact-hero--media']) }}
    aria-labelledby="{{ $headingId }}"
    @if($slideCount > 1)
        x-data="heroCarousel({{ $slideCount }})"
        @mouseenter="paused = true"
        @mouseleave="paused = false"
        @focusin="paused = true"
        @focusout="paused = false"
    @endif
>
    <div class="impact-hero__media" aria-hidden="true">
        @foreach($slides as $i => $slide)
            <img
                src="{{ $slide['url'] }}"
                alt=""
                @class(['impact-hero__photo', 'is-active' => $i === 0])
                @if($slideCount > 1)
                    :class="{ 'is-active': index === {{ $i }} }"
                @endif
                width="1600"
                height="900"
                @if($i === 0)
                    loading="eager"
                    fetchpriority="high"
                @else
                    loading="lazy"
                @endif
                decoding="async"
            >
        @endforeach
    </div>
    <div class="impact-hero__veil" aria-hidden="true"></div>

    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="impact-hero__inner reveal">
            <nav aria-label="Breadcrumb" class="impact-hero__crumbs">
                <ol>
                    <li><a href="{{ route('site.home') }}">Home</a></li>
                    @if($showParent && $currentLabel)
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ $parentHref }}">{{ $parentLabel }}</a></li>
                        <li aria-hidden="true">/</li>
                        <li><span aria-current="page">{{ $currentLabel }}</span></li>
                    @elseif($showParent)
                        <li aria-hidden="true">/</li>
                        <li><span aria-current="page">{{ $parentLabel }}</span></li>
                    @elseif($currentLabel)
                        <li aria-hidden="true">/</li>
                        <li><span aria-current="page">{{ $currentLabel }}</span></li>
                    @endif
                </ol>
            </nav>

            @if($eyebrow)
                <span class="impact-hero__eyebrow">{{ $eyebrow }}</span>
            @endif

            <h1 id="{{ $headingId }}" class="impact-hero__title" style="max-width: {{ $titleMax }};">{{ $title }}</h1>

            @if($tagline)
                <p class="impact-hero__tagline">{{ $tagline }}</p>
            @endif

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

    @if($slideCount > 1)
        <div
            class="impact-hero__progress"
            role="progressbar"
            aria-label="Banner slide autoplay progress"
            :aria-valuenow="Math.round(progress)"
            aria-valuemin="0"
            aria-valuemax="100"
        >
            <span class="impact-hero__progress-bar" :style="`width: ${progress}%`"></span>
        </div>
        <div class="impact-hero__controls">
            <button type="button" class="impact-hero__nav" @click="prev()" aria-label="Previous banner image">
                <span aria-hidden="true">‹</span>
            </button>
            <div class="impact-hero__dots" role="tablist" aria-label="Banner slides">
                @foreach($slides as $i => $slide)
                    <button
                        type="button"
                        class="impact-hero__dot"
                        role="tab"
                        :aria-selected="index === {{ $i }}"
                        :class="{ 'is-active': index === {{ $i }} }"
                        @click="go({{ $i }})"
                        aria-label="Show banner image {{ $i + 1 }} of {{ $slideCount }}"
                    ></button>
                @endforeach
            </div>
            <button type="button" class="impact-hero__nav" @click="next()" aria-label="Next banner image">
                <span aria-hidden="true">›</span>
            </button>
        </div>
        <p class="sr-only" aria-live="polite" x-text="'Banner image ' + (index + 1) + ' of {{ $slideCount }}'"></p>
    @endif
</section>
