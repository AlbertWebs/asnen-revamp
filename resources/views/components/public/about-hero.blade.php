@props([
    'breadcrumb' => 'About',
    'breadcrumbUrl' => null,
    'currentLabel' => null,
    'eyebrow' => null,
    'brand' => 'ASNEN',
    'title',
    'titleMax' => '14ch',
    'tagline' => null,
    'excerpt' => null,
    'primaryCta' => null,
    'secondaryCta' => null,
    'showVisual' => false,
    'images' => [],
])

@php
    $slides = collect($images)
        ->map(function ($image) {
            if (! $image) {
                return null;
            }
            if (is_string($image)) {
                return ['src' => $image, 'alt' => ''];
            }
            $url = is_object($image) ? $image->publicUrl() : ($image['url'] ?? $image['src'] ?? null);
            if (! $url) {
                return null;
            }

            return [
                'src' => $url,
                'alt' => is_object($image)
                    ? ($image->alt ?? '')
                    : ($image['alt'] ?? ''),
            ];
        })
        ->filter()
        ->values();
    $hasPhotos = $slides->isNotEmpty();
    $showAside = $showVisual || $hasPhotos;
@endphp

<section @class(['impact-hero', 'who-hero', 'who-hero--with-photos' => $hasPhotos])>
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div @class([
            'reveal',
            'who-hero__grid' => $showAside,
            'who-hero__grid--photos' => $hasPhotos,
            'impact-hero__inner' => ! $showAside,
        ])>
            <div class="who-hero__copy">
                <x-public.breadcrumbs :items="array_values(array_filter([
                    ['label' => $breadcrumb, 'url' => $breadcrumbUrl ?? route('site.about.who-we-are')],
                    $currentLabel ? ['label' => $currentLabel] : null,
                ]))" />

                @if($brand)
                    <p class="who-hero__brand">{{ $brand }}</p>
                @elseif($eyebrow)
                    <span class="eyebrow mt-6 block">{{ $eyebrow }}</span>
                @endif

                <h1 class="impact-hero__title" style="max-width: {{ $titleMax }};">{{ $title }}</h1>

                @if($tagline)
                    <p class="who-hero__tagline">{{ $tagline }}</p>
                @endif

                @if($excerpt)
                    <p class="impact-hero__excerpt">{{ $excerpt }}</p>
                @endif

                @if($slot->isNotEmpty())
                    <div class="impact-hero__body">
                        {{ $slot }}
                    </div>
                @endif

                @if($primaryCta || $secondaryCta)
                    <div class="impact-hero__actions">
                        @if($primaryCta)
                            <a href="{{ $primaryCta['url'] }}" class="btn-primary">{{ $primaryCta['label'] }}</a>
                        @endif
                        @if($secondaryCta)
                            <a href="{{ $secondaryCta['url'] }}" class="btn-secondary">{{ $secondaryCta['label'] }}</a>
                        @endif
                    </div>
                @endif
            </div>

            @if($hasPhotos)
                <div
                    class="who-hero__visual who-hero__visual--photos gallery-lightbox"
                    x-data="cascadeCarousel(@js($slides))"
                    @mouseenter="paused = true"
                    @mouseleave="paused = false"
                    @focusin="paused = true"
                    @focusout="paused = false"
                    @keydown.escape.window="if (lightbox) closeLightbox()"
                    @keydown.arrow-right.window="if (lightbox) lightboxNext()"
                    @keydown.arrow-left.window="if (lightbox) lightboxPrev()"
                >
                    <div
                        class="who-hero__cascade"
                        :class="{ 'is-shuffling': shuffling }"
                        role="group"
                        aria-roledescription="carousel"
                        aria-label="ASNEN community photos"
                    >
                        <template x-for="card in deck" :key="card.key">
                            <figure
                                class="who-hero__cascade-frame"
                                :data-cascade-card="card.key"
                                :class="{
                                    'is-front': card.slot === 'front',
                                    'is-mid': card.slot === 'mid',
                                    'is-back': card.slot === 'back',
                                }"
                            >
                                <button
                                    type="button"
                                    class="who-hero__cascade-btn"
                                    @click="openCard(card)"
                                    :aria-label="(slideForCard(card)?.alt || 'View photo') + ' — zoom'"
                                >
                                    <img
                                        :src="slideForCard(card)?.src"
                                        :alt="slideForCard(card)?.alt || ''"
                                        class="who-hero__cascade-photo"
                                        width="640"
                                        height="800"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                    <span
                                        class="who-hero__cascade-hint"
                                        aria-hidden="true"
                                        x-show="card.slot === 'front'"
                                        x-cloak
                                    >
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="11" cy="11" r="7"/>
                                            <path stroke-linecap="round" d="M20 20l-3-3"/>
                                            <path stroke-linecap="round" d="M11 8v6M8 11h6"/>
                                        </svg>
                                    </span>
                                </button>
                            </figure>
                        </template>
                    </div>

                    <div class="who-hero__carousel-controls" x-show="count > 1" x-cloak>
                        <button type="button" class="who-hero__carousel-nav" @click="prev()" aria-label="Previous photos">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <div class="who-hero__carousel-dots" role="tablist" aria-label="Photo slides">
                            <template x-for="(slide, i) in slides" :key="i">
                                <button
                                    type="button"
                                    class="who-hero__carousel-dot"
                                    :class="{ 'is-active': index === i }"
                                    @click="go(i)"
                                    :aria-label="'Show photo ' + (i + 1)"
                                    :aria-current="index === i ? 'true' : 'false'"
                                ></button>
                            </template>
                        </div>
                        <button type="button" class="who-hero__carousel-nav" @click="next()" aria-label="Next photos">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    <div
                        x-show="lightbox"
                        x-cloak
                        x-transition.opacity.duration.200ms
                        class="gallery-lightbox__overlay"
                        role="dialog"
                        aria-modal="true"
                        :aria-label="current?.alt || 'Expanded photo'"
                        @click.self="closeLightbox()"
                    >
                        <button type="button" class="gallery-lightbox__close" @click="closeLightbox()" aria-label="Close photo">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
                            </svg>
                        </button>

                        <button
                            type="button"
                            class="gallery-lightbox__nav gallery-lightbox__nav--prev"
                            @click="lightboxPrev()"
                            aria-label="Previous photo"
                            x-show="count > 1"
                        >
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <figure class="gallery-lightbox__stage" x-show="current">
                            <img
                                :src="current?.src"
                                :alt="current?.alt || ''"
                                class="gallery-lightbox__image"
                                @click.stop
                            >
                            <p class="gallery-lightbox__count" x-text="(lightboxIndex + 1) + ' / ' + count"></p>
                        </figure>

                        <button
                            type="button"
                            class="gallery-lightbox__nav gallery-lightbox__nav--next"
                            @click="lightboxNext()"
                            aria-label="Next photo"
                            x-show="count > 1"
                        >
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @elseif($showVisual)
                <div class="who-hero__visual" aria-hidden="true">
                    <svg class="ring-art who-hero__rings" viewBox="0 0 480 480" role="presentation">
                        <g class="ring-spin">
                            <circle cx="160" cy="160" r="60" stroke="#0C77BC"/>
                            <circle cx="270" cy="150" r="42" stroke="#8CC63F"/>
                            <circle cx="310" cy="250" r="58" stroke="#4A4C70"/>
                            <circle cx="205" cy="290" r="36" stroke="#75BDE7"/>
                            <circle cx="120" cy="255" r="26" stroke="#FFF200"/>
                            <circle cx="345" cy="120" r="20" stroke="#0C77BC"/>
                        </g>
                    </svg>
                </div>
            @endif
        </div>
    </div>
</section>
