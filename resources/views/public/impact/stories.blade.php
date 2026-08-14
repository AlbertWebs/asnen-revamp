@extends('layouts.public')

@section('title', ($page?->seoMeta?->title ?? $page?->title ?? 'Success Stories').' | '.$siteName)
@section('meta_description', $page?->seoMeta?->description ?? $page?->excerpt ?? 'Evidence-led stories from ASNEN programmes and community outreach.')

@section('content')
    @php
        $heading = $page?->title ?? 'Success Stories';
        $excerpt = $page?->excerpt ?? 'Evidence-led narratives from programmes, outreach, and community work across the ASNEN network.';
        $featuredHref = $featuredStory?->publicUrl();
        $featuredEyebrow = 'Featured case study';
        if ($featuredStory) {
            $featuredEyebrow = $featuredStory->slug === \App\Models\ImpactStory::KOMOLION_SLUG
                ? 'Komolion · Baringo County'
                : collect([$featuredStory->location, $featuredStory->story_date?->format('Y')])->filter()->implode(' · ');
        }
    @endphp

    <x-public.media-hero
        parent-label="Impact"
        :parent-url="route('site.impact.overview')"
        current-label="Success Stories"
        eyebrow="From the field"
        :title="$heading"
        title-max="14ch"
        tagline="Evidence from programmes and communities."
        :excerpt="$excerpt"
        :primary-cta="$featuredHref ? ['label' => 'Read featured story', 'url' => $featuredHref] : ['label' => 'Impact overview', 'url' => route('site.impact.overview')]"
        :secondary-cta="['label' => 'View reports', 'url' => route('site.resources.publications')]"
        :images="$bannerImages ?? []"
    />

    <x-public.impact-subnav current="stories" />

    @if($featuredStory && $stories->onFirstPage())
        <section class="section-editorial impact-feature">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">Featured</span>
                        <h2>Start here</h2>
                        <p class="section-head-row__intro">A deeper case study that shows how ASNEN works with communities on the ground.</p>
                    </div>
                    @if($featuredHref)
                        <a href="{{ $featuredHref }}" class="btn-secondary section-head-row__cta">Open case study</a>
                    @endif
                </div>

                <div class="reveal">
                    <x-public.story-feature
                        :story="$featuredStory"
                        :cta-url="$featuredHref"
                        cta-label="Read the full case study"
                        :eyebrow="$featuredEyebrow"
                    />
                </div>
            </div>
        </section>
    @endif

    <section class="section-editorial {{ $featuredStory && $stories->onFirstPage() ? 'bg-sand' : '' }}">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">All stories</span>
                    <h2>Published impact narratives</h2>
                    <p class="section-head-row__intro">
                        @if($stories->total() > 0)
                            {{ $stories->total() }} {{ \Illuminate\Support\Str::plural('story', $stories->total()) }} from ASNEN programmes and partners.
                        @else
                            Stories will appear here once published and verified.
                        @endif
                    </p>
                </div>
                <a href="{{ route('site.impact.overview') }}" class="btn-secondary section-head-row__cta">Impact overview</a>
            </div>

            @if($stories->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state
                        message="Impact stories will appear here once published."
                        :action="route('site.impact.overview')"
                        action-label="Back to Impact"
                    />
                </div>
            @else
                <div class="impact-story-grid reveal mt-8">
                    @foreach($stories as $story)
                        @php $href = $story->publicUrl(); @endphp
                        <article class="impact-story-card">
                            <a href="{{ $href }}" class="impact-story-card__media">
                                <x-public.media-frame
                                    :asset="$story->featuredImage"
                                    :alt="$story->featuredImage?->alt ?? $story->title"
                                    ratio="16/9"
                                    rounded="rounded-none"
                                    label="Story image"
                                />
                            </a>
                            <div class="impact-story-card__body">
                                @if($story->story_date || $story->location)
                                    <p class="impact-story-card__meta">
                                        {{ collect([
                                            $story->story_date?->format('d M Y'),
                                            $story->location,
                                        ])->filter()->implode(' · ') }}
                                    </p>
                                @endif
                                <h3 class="impact-story-card__title">
                                    <a href="{{ $href }}">{{ $story->title }}</a>
                                </h3>
                                @if($story->summary)
                                    <p class="impact-story-card__summary">{{ $story->summary }}</p>
                                @endif
                                <a href="{{ $href }}" class="impact-story-card__link">
                                    Read story
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="reveal">
                    <x-public.pagination :paginator="$stories" />
                </div>
            @endif
        </div>
    </section>

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Keep exploring</span>
                <h2>More ways into the evidence</h2>
                <p class="section-head-row__intro">Follow ASNEN's impact across reports, regions, and programmes.</p>
            </div>

            <div class="who-explore reveal">
                <a href="{{ route('site.resources.publications') }}" class="who-explore__item">
                    <span class="who-explore__label">Impact reports</span>
                    <span class="who-explore__desc">Downloadable PDFs from conferences and programmes</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.impact.regions') }}" class="who-explore__item">
                    <span class="who-explore__label">Regions</span>
                    <span class="who-explore__desc">Where ASNEN’s work takes root</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.programs.index') }}" class="who-explore__item">
                    <span class="who-explore__label">What we do</span>
                    <span class="who-explore__desc">Programmes behind these stories</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.get-involved.index') }}" class="who-explore__item">
                    <span class="who-explore__label">Get involved</span>
                    <span class="who-explore__desc">Help carry this work forward</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Be part of the next story"
        text="Membership, volunteering, partnership, and giving all strengthen inclusive education across Africa."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
