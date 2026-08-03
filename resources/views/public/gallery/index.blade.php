@extends('layouts.public')

@section('title', ($page?->seoMeta?->title ?? $page?->title ?? 'Gallery').' | '.$siteName)
@section('meta_description', $page?->seoMeta?->description ?? $page?->excerpt ?? 'Photo albums from ASNEN programmes, conferences, and community outreach.')

@section('content')
    @php
        $featured = $galleries->onFirstPage() ? $galleries->getCollection()->first() : null;
        $rest = $featured
            ? $galleries->getCollection()->slice(1)->values()
            : $galleries->getCollection();
    @endphp

    <x-public.about-hero
        breadcrumb="Resources"
        :breadcrumb-url="route('site.resources.index')"
        current-label="Gallery"
        :title="$page?->title ?? 'Gallery'"
        title-max="12ch"
        tagline="Moments from the work."
        :excerpt="$page?->excerpt ?? 'Photo albums from ASNEN programmes, conferences, and community outreach across Kenya and beyond.'"
        :primary-cta="['label' => 'Impact stories', 'url' => route('site.impact.stories')]"
        :secondary-cta="['label' => 'Events & learning', 'url' => route('site.events.index')]"
    />

    <x-public.resources-subnav current="gallery" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">Why we share photos</span>
                    <h2>Belonging made visible</h2>
                    <div class="who-identity__body">
                        <p class="text-lg leading-relaxed text-charcoal-500">
                            ASNEN albums document programmes, gatherings, and outreach with care for dignity and safeguarding. Every public album is reviewed before it appears here.
                        </p>
                    </div>
                </div>
                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">How to browse</p>
                    <p class="who-identity__aside-quote">Open an album, then tap a photo to see it larger.</p>
                    <ul class="who-identity__aside-list">
                        <li>Albums by place and programme</li>
                        <li>Captions where available</li>
                        <li>Photos reviewed before publishing</li>
                    </ul>
                    <a href="{{ route('site.impact.komolion') }}" class="who-identity__aside-link">
                        See Komolion story
                        <span aria-hidden="true">→</span>
                    </a>
                </aside>
            </div>
        </div>
    </section>

    @if($featured)
        @php
            $featuredCover = $featured->coverItem?->mediaAsset;
            $featuredCount = $featured->items_count ?? $featured->items->count();
            $featuredMeta = collect([
                $featured->gallery_date?->format('F Y'),
                $featured->location,
                $featuredCount ? $featuredCount.' '.\Illuminate\Support\Str::plural('photo', $featuredCount) : null,
            ])->filter()->implode(' · ');
        @endphp
        <section class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">Featured album</span>
                        <h2>Start here</h2>
                        <p class="section-head-row__intro">A recent album from ASNEN programmes and gatherings.</p>
                    </div>
                    <a href="{{ route('site.resources.gallery.show', $featured->slug) }}" class="btn-secondary section-head-row__cta">Open album</a>
                </div>

                <a href="{{ route('site.resources.gallery.show', $featured->slug) }}" class="gallery-feature reveal">
                    <div class="gallery-feature__media">
                        <x-public.media-frame
                            :asset="$featuredCover"
                            :alt="$featuredCover?->alt ?? $featured->title"
                            ratio="16/9"
                            rounded="rounded-2xl"
                            label="Album cover"
                        />
                    </div>
                    <div class="gallery-feature__copy">
                        @if($featuredMeta)
                            <p class="gallery-feature__meta">{{ $featuredMeta }}</p>
                        @endif
                        <h3 class="gallery-feature__title">{{ $featured->title }}</h3>
                        @if($featured->description)
                            <p class="gallery-feature__desc">{{ $featured->description }}</p>
                        @endif
                        <span class="gallery-feature__link">
                            Browse photos
                            <span aria-hidden="true">→</span>
                        </span>
                    </div>
                </a>
            </div>
        </section>
    @endif

    <section class="section-editorial {{ $featured ? '' : 'bg-sand' }}">
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
                <div class="gallery-album-grid reveal mt-8">
                    @foreach($rest as $gallery)
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

    <section class="section-editorial bg-sand">
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
