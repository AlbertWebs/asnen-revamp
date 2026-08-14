@extends('layouts.public')

@section('title', ($page?->seoMeta?->title ?? $page?->title ?? 'Gallery').' | '.$siteName)
@section('meta_description', $page?->seoMeta?->description ?? $page?->excerpt ?? 'Photo albums from ASNEN programmes, conferences, and community outreach.')

@section('content')
    <x-public.media-hero
        parent-label="Resources"
        :parent-url="route('site.resources.index')"
        current-label="Gallery"
        eyebrow="Visual stories"
        :title="$page?->title ?? 'Gallery'"
        title-max="12ch"
        tagline="Moments from the work."
        :excerpt="$page?->excerpt ?? 'Photo albums from ASNEN programmes, conferences, and community outreach across Kenya and beyond.'"
        :primary-cta="['label' => 'Photo albums', 'url' => '#gallery-albums']"
        :secondary-cta="['label' => 'Events & learning', 'url' => route('site.events.index')]"
        :images="$bannerImages ?? []"
    />

    <x-public.resources-subnav current="gallery" />

    @if(count($floodSlides ?? []) > 0)
        <section class="section-editorial" aria-labelledby="gallery-flood-heading">
            <div class="gallery-flood">
                <div class="gallery-flood__head">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">General Gallery</span>
                        <h2 id="gallery-flood-heading">{{ count($floodSlides) }} photographs</h2>
                    </div>
                    <a href="{{ route('site.resources.gallery.show', $floodGallery->slug) }}" class="btn-secondary section-head-row__cta">Open album</a>
                </div>

                <div
                    class="gallery-lightbox"
                    x-data="{
                        slides: @js($floodSlides),
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
                    <div class="gallery-flood__grid">
                        @foreach($floodSlides as $index => $slide)
                            <figure class="gallery-thumb">
                                <button
                                    type="button"
                                    class="gallery-thumb__btn group"
                                    @click="openAt({{ $index }})"
                                    aria-label="View photo {{ $index + 1 }}{{ !empty($slide['caption']) ? ': '.$slide['caption'] : '' }}"
                                >
                                    <img
                                        src="{{ $slide['src'] }}"
                                        alt="{{ $slide['alt'] }}"
                                        class="gallery-thumb__image"
                                        loading="{{ $index < 12 ? 'eager' : 'lazy' }}"
                                        decoding="async"
                                        width="800"
                                        height="800"
                                    >
                                </button>
                            </figure>
                        @endforeach
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
            </div>
        </section>
    @endif

    <section id="gallery-albums" class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">All albums</span>
                    <h2>Photo galleries</h2>
                    <p class="section-head-row__intro">
                        @if($galleries->total() > 0)
                            {{ $galleries->total() }} published {{ \Illuminate\Support\Str::plural('album', $galleries->total()) }} from the ASNEN network.
                        @else
                            Gallery albums will appear here once approved for public display.
                        @endif
                    </p>
                </div>
            </div>

            @if($galleries->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state
                        message="Gallery albums will appear here once approved for public display."
                        :action="route('site.resources.index')"
                        action-label="Back to resources"
                    />
                </div>
            @else
                <div class="gallery-album-grid mt-8">
                    @foreach($galleries as $gallery)
                        @php
                            $cover = $gallery->coverItem?->mediaAsset;
                            $count = $gallery->items_count ?? $gallery->items->count();
                            $meta = collect([
                                $gallery->gallery_date?->format('M Y'),
                                $gallery->location,
                                $count ? $count.' '.\Illuminate\Support\Str::plural('photo', $count) : null,
                            ])->filter()->implode(' · ');
                        @endphp
                        <a href="{{ route('site.resources.gallery.show', $gallery->slug) }}" class="gallery-album">
                            <div class="gallery-album__media">
                                <x-public.media-frame
                                    :asset="$cover"
                                    :alt="$cover?->alt ?? $gallery->title"
                                    ratio="16/9"
                                    rounded="rounded-none"
                                    label="Album cover"
                                />
                            </div>
                            <div class="gallery-album__body">
                                @if($meta)
                                    <p class="gallery-album__meta">{{ $meta }}</p>
                                @endif
                                <h3 class="gallery-album__title">{{ $gallery->title }}</h3>
                                @if($gallery->description)
                                    <p class="gallery-album__desc">{{ \Illuminate\Support\Str::limit($gallery->description, 110) }}</p>
                                @endif
                                <span class="gallery-album__link">
                                    Open album
                                    <span aria-hidden="true">→</span>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="reveal">
                    <x-public.pagination :paginator="$galleries" />
                </div>
            @endif
        </div>
    </section>

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Keep exploring</span>
                <h2>From photos to the full story</h2>
                <p class="section-head-row__intro">Albums sit alongside impact stories, events, and programmes.</p>
            </div>

            <div class="who-explore reveal">
                <a href="{{ route('site.impact.stories') }}" class="who-explore__item">
                    <span class="who-explore__label">Impact stories</span>
                    <span class="who-explore__desc">Evidence-led narratives from the field</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.events.index') }}" class="who-explore__item">
                    <span class="who-explore__label">Events &amp; learning</span>
                    <span class="who-explore__desc">Conferences and gatherings in pictures and practice</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.resources.news') }}" class="who-explore__item">
                    <span class="who-explore__label">News &amp; insights</span>
                    <span class="who-explore__desc">Updates from across the network</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.get-involved.index') }}" class="who-explore__item">
                    <span class="who-explore__label">Get involved</span>
                    <span class="who-explore__desc">Walk with ASNEN beyond a single album</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Help create the next moments"
        text="Volunteering, partnership, and membership all strengthen the programmes these albums document."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
