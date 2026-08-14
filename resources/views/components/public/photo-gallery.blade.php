@props([
    'slides' => [],
    'heading' => 'Photo gallery',
    'intro' => null,
    'eyebrow' => 'Gallery',
])

@if(count($slides) > 0)
    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">{{ $eyebrow }}</span>
                <h2>{{ $heading }}</h2>
                @if($intro)
                    <p class="section-head-row__intro">{{ $intro }}</p>
                @endif
            </div>

            <div
                class="gallery-lightbox"
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
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($slides as $index => $slide)
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
                            @if(!empty($slide['caption']))
                                <figcaption class="p-3 text-sm text-charcoal/70">{{ $slide['caption'] }}</figcaption>
                            @endif
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
