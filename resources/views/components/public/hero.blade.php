@props([
    'brand' => 'Demystifying Disability',
    'eyebrow' => 'A homegrown African model of inclusion',
    'headline' => '',
    'supportingText' => null,
    'primaryCta' => null,
    'secondaryCta' => null,
    'images' => [],
    'imageAlt' => null,
])

@php
    $hasNoChild = preg_match('/^(.*?)\b(No child)\b(.*)$/is', $headline, $m) === 1;
    $slides = collect($images)
        ->map(function ($image) use ($imageAlt) {
            if (! $image) {
                return null;
            }
            $url = is_object($image) ? $image->publicUrl() : ($image['url'] ?? null);
            if (! $url) {
                return null;
            }

            return [
                'url' => $url,
                'alt' => is_object($image)
                    ? ($imageAlt ?? $image->alt ?? 'ASNEN community')
                    : ($image['alt'] ?? $imageAlt ?? 'ASNEN community'),
            ];
        })
        ->filter()
        ->values();
    $hasMedia = $slides->isNotEmpty();
    $slideCount = $slides->count();
@endphp

<section
    @class(['landing-hero', 'landing-hero--with-media' => $hasMedia])
    aria-labelledby="hero-heading"
    @if($hasMedia && $slideCount > 1)
        x-data="heroCarousel({{ $slideCount }})"
        @mouseenter="paused = true"
        @mouseleave="paused = false"
        @focusin="paused = true"
        @focusout="paused = false"
    @endif
>
    @if($hasMedia)
        <div class="landing-hero__media" aria-hidden="true">
            @foreach($slides as $i => $slide)
                <img
                    src="{{ $slide['url'] }}"
                    alt=""
                    @class(['landing-hero__photo', 'is-active' => $i === 0])
                    @if($slideCount > 1)
                        :class="{ 'is-active': index === {{ $i }} }"
                    @endif
                    width="1600"
                    height="1067"
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
        <div class="landing-hero__veil" aria-hidden="true"></div>
    @else
        <div class="landing-hero__atmosphere" aria-hidden="true"></div>
    @endif

    <div class="landing-hero__shell">
        <div class="landing-hero__copy">
            <p class="landing-hero__brand">{{ $brand }}</p>

            @if($eyebrow)
                <p class="landing-hero__eyebrow">{{ $eyebrow }}</p>
            @endif

            <h1 id="hero-heading" class="landing-hero__title">
                @if($hasNoChild)
                    <span class="landing-hero__line">{{ trim($m[1]) }}</span>
                    <span class="landing-hero__line">
                        <em class="landing-hero__accent">{{ $m[2] }}</em>{{ rtrim($m[3]) }}
                    </span>
                @else
                    {{ $headline }}
                @endif
            </h1>

            @if($supportingText)
                <p class="landing-hero__lede">{{ $supportingText }}</p>
            @endif

            @if($primaryCta || $secondaryCta)
                <div class="landing-hero__actions">
                    @if($primaryCta)
                        <a href="{{ $primaryCta['url'] ?? '#' }}" class="landing-hero__cta landing-hero__cta--primary">{{ $primaryCta['label'] ?? 'Learn more' }}</a>
                    @endif
                    @if($secondaryCta)
                        <a href="{{ $secondaryCta['url'] ?? '#' }}" class="landing-hero__cta landing-hero__cta--secondary">{{ $secondaryCta['label'] ?? 'Learn more' }}</a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($hasMedia && $slideCount > 1)
        <div
            class="landing-hero__progress"
            role="progressbar"
            aria-label="Slide autoplay progress"
            :aria-valuenow="Math.round(progress)"
            aria-valuemin="0"
            aria-valuemax="100"
        >
            <span class="landing-hero__progress-bar" :style="`width: ${progress}%`"></span>
        </div>
        <div class="landing-hero__controls">
            <button type="button" class="landing-hero__nav" @click="prev()" aria-label="Previous slide">
                <span aria-hidden="true">‹</span>
            </button>
            <div class="landing-hero__dots" role="tablist" aria-label="Hero slides">
                @foreach($slides as $i => $slide)
                    <button
                        type="button"
                        class="landing-hero__dot"
                        role="tab"
                        :aria-selected="index === {{ $i }}"
                        :class="{ 'is-active': index === {{ $i }} }"
                        @click="go({{ $i }})"
                        aria-label="Show slide {{ $i + 1 }} of {{ $slideCount }}"
                    ></button>
                @endforeach
            </div>
            <button type="button" class="landing-hero__nav" @click="next()" aria-label="Next slide">
                <span aria-hidden="true">›</span>
            </button>
        </div>
        <p class="sr-only" aria-live="polite" x-text="'Slide ' + (index + 1) + ' of {{ $slideCount }}'"></p>
    @endif

    @if($hasMedia)
        <span class="sr-only">{{ $slides->first()['alt'] }}</span>
    @endif
</section>
