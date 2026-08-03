@extends('layouts.public')

@section('title', $gallery->title.' | '.$siteName)
@section('meta_description', $gallery->description ?? ($gallery->title.' - ASNEN photo gallery.'))

@section('content')
    @php
        $slides = $gallery->items
            ->filter(fn ($item) => filled($item->mediaAsset?->publicUrl()))
            ->values()
            ->map(fn ($item) => [
                'src' => $item->mediaAsset->publicUrl(),
                'alt' => $item->mediaAsset->alt ?? $item->caption ?? $gallery->title,
                'caption' => $item->caption,
            ])
            ->all();

        $meta = collect([
            $gallery->gallery_date?->format('F Y'),
            $gallery->location,
            count($slides) ? count($slides).' '.\Illuminate\Support\Str::plural('photo', count($slides)) : null,
        ])->filter()->implode(' · ');
    @endphp

    <x-public.about-hero
        breadcrumb="Gallery"
        :breadcrumb-url="route('site.resources.gallery.index')"
        current-label="Album"
        :title="$gallery->title"
        title-max="16ch"
        :tagline="$meta ?: 'ASNEN album'"
        :excerpt="$gallery->description"
        :primary-cta="['label' => 'All albums', 'url' => route('site.resources.gallery.index')]"
        :secondary-cta="['label' => 'Impact stories', 'url' => route('site.impact.stories')]"
    />

    <x-public.resources-subnav current="gallery" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            @if(count($slides) === 0)
                <div class="reveal">
                    <x-public.empty-state
                        message="Photos will appear here once uploaded."
                        :action="route('site.resources.gallery.index')"
                        action-label="Back to gallery"
                    />
                </div>
            @else
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">Album</span>
                        <h2>Browse photos</h2>
                    </div>
                </div>

                <div
                    class="gallery-lightbox reveal mt-8"
                    x-data="{
                        slides: @js($slides),
                        open: false,
                        index: 0,
                        get current() { return this.slides[this.index] || null },
                        openAt(i) {
                            this.index = i;
                            this.open = true;
                            document.documentElement.classList.add('overflow-hidden');
                        },
                        close() {
                            this.open = false;
                            document.documentElement.classList.remove('overflow-hidden');
                        },
                        next() {
                            if (!this.slides.length) return;
                            this.index = (this.index + 1) % this.slides.length;
                        },
                        prev() {
                            if (!this.slides.length) return;
                            this.index = (this.index - 1 + this.slides.length) % this.slides.length;
                        }
                    }"
                    @keydown.escape.window="if (open) close()"
                    @keydown.arrow-right.window="if (open) next()"
                    @keydown.arrow-left.window="if (open) prev()"
                >
                    <div class="gallery-photo-grid">
                        <template x-for="(slide, index) in slides" :key="index">
                            <figure class="gallery-thumb">
                                <button
                                    type="button"
                                    class="gallery-thumb__btn group"
                                    @click="openAt(index)"
                                    :aria-label="'View photo ' + (index + 1) + (slide.caption ? ': ' + slide.caption : '')"
                                >
                                    <img
                                        :src="slide.src"
                                        :alt="slide.alt"
                                        class="gallery-thumb__image"
                                        loading="lazy"
                                        decoding="async"
                                        width="1600"
                                        height="1200"
                                    >
                                    <span class="gallery-thumb__hint" aria-hidden="true">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="11" cy="11" r="7"/>
                                            <path stroke-linecap="round" d="M20 20l-3-3"/>
                                            <path stroke-linecap="round" d="M11 8v6M8 11h6"/>
                                        </svg>
                                    </span>
                                </button>
                                <figcaption
                                    class="gallery-thumb__caption"
                                    x-show="slide.caption"
                                    x-text="slide.caption"
                                ></figcaption>
                            </figure>
                        </template>
                    </div>

                    <div
                        x-show="open"
                        x-cloak
                        x-transition.opacity.duration.200ms
                        class="gallery-lightbox__overlay"
                        role="dialog"
                        aria-modal="true"
                        :aria-label="current?.alt || 'Expanded photo'"
                        @click.self="close()"
                    >
                        <button type="button" class="gallery-lightbox__close" @click="close()" aria-label="Close photo">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
                            </svg>
                        </button>

                        <button
                            type="button"
                            class="gallery-lightbox__nav gallery-lightbox__nav--prev"
                            @click="prev()"
                            aria-label="Previous photo"
                            x-show="slides.length > 1"
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
                            <figcaption class="gallery-lightbox__caption" x-show="current?.caption" x-text="current?.caption"></figcaption>
                            <p class="gallery-lightbox__count" x-text="(index + 1) + ' / ' + slides.length"></p>
                        </figure>

                        <button
                            type="button"
                            class="gallery-lightbox__nav gallery-lightbox__nav--next"
                            @click="next()"
                            aria-label="Next photo"
                            x-show="slides.length > 1"
                        >
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <x-public.cta-band
        heading="Explore more from ASNEN"
        text="Return to all albums, or follow the work through stories and programmes."
        :primary-cta="['label' => 'All albums', 'url' => route('site.resources.gallery.index')]"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
    />
@endsection
