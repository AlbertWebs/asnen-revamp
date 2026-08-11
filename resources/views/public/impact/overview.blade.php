@extends('layouts.public')

@section('title', ($page->title ?? 'Impact').' | '.$siteName)
@section('meta_description', $page->excerpt ?? 'Evidence-led impact from ASNEN programmes, outreach, and learning.')

@section('content')
    @php
        $heading = $page->title ?? 'Impact';
        $excerpt = $page->excerpt ?? 'Evidence-led stories and learning from programmes across the ASNEN network.';
        $metricFootnote = null;
        $isKomolion = $featuredStory?->slug === 'komolion-2023-disability-assessment-medical-camp';
        $featuredHref = $isKomolion
            ? route('site.impact.komolion')
            : ($featuredStory ? route('site.impact.stories.show', $featuredStory->slug) : null);
        $featuredEyebrow = $isKomolion ? 'Komolion · Baringo County' : 'Featured impact story';
    @endphp

    <x-public.media-hero
        :show-parent="false"
        parent-label="Impact"
        eyebrow="Evidence & learning"
        :title="$heading"
        title-max="12ch"
        :excerpt="$excerpt"
        :body-html="!empty($introHtml) ? $sanitizer->clean($introHtml) : null"
        :primary-cta="$featuredHref ? ['label' => 'Read featured case study', 'url' => $featuredHref] : null"
        :secondary-cta="['label' => 'Browse stories', 'url' => route('site.impact.stories')]"
        :images="$bannerImages ?? []"
    />

    @if($metrics->isNotEmpty())
        <x-public.stats :metrics="$metrics" :footnote="$metricFootnote" />
    @else
        <section class="section-editorial">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <x-public.empty-state
                    message="Impact highlights will appear here soon."
                    :action="route('site.impact.reports')"
                    action-label="View impact reports"
                />
            </div>
        </section>
    @endif

    @if($featuredStory)
        <section class="section-editorial impact-feature">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">Featured case study</span>
                        <h2>From the field</h2>
                        <p class="section-head-row__intro">Evidence-led outcomes from community outreach across the ASNEN network.</p>
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

    @if($stories->isNotEmpty())
        <section class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">More stories</span>
                        <h2>Recent impact stories</h2>
                        <p class="section-head-row__intro">Additional published narratives from ASNEN programmes and partners.</p>
                    </div>
                    <a href="{{ route('site.impact.stories') }}" class="btn-secondary section-head-row__cta">All stories</a>
                </div>

                <div class="impact-story-grid reveal">
                    @foreach($stories as $story)
                        <article class="impact-story-card">
                            <a href="{{ route('site.impact.stories.show', $story->slug) }}" class="impact-story-card__media">
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
                                    <a href="{{ route('site.impact.stories.show', $story->slug) }}">{{ $story->title }}</a>
                                </h3>
                                @if($story->summary)
                                    <p class="impact-story-card__summary">{{ $story->summary }}</p>
                                @endif
                                <a href="{{ route('site.impact.stories.show', $story->slug) }}" class="impact-story-card__link">
                                    Read story
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section-editorial impact-explore">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Explore</span>
                    <h2>Ways to dig deeper</h2>
                    <p class="section-head-row__intro">Follow the evidence across stories, reports, and the places ASNEN works.</p>
                </div>
            </div>

            <div class="impact-path-grid reveal">
                <a href="{{ route('site.impact.komolion') }}" class="impact-path">
                    <span class="impact-path__num" aria-hidden="true">01</span>
                    <h3 class="impact-path__title">Komolion story</h3>
                    <p class="impact-path__desc">Disability assessment, NCPWD registration, and orthopedic outreach in Baringo County.</p>
                    <span class="impact-path__link">Open case study <span aria-hidden="true">→</span></span>
                </a>
                <a href="{{ route('site.impact.stories') }}" class="impact-path">
                    <span class="impact-path__num" aria-hidden="true">02</span>
                    <h3 class="impact-path__title">Success stories</h3>
                    <p class="impact-path__desc">Published case studies and narratives from programmes and community initiatives.</p>
                    <span class="impact-path__link">Browse stories <span aria-hidden="true">→</span></span>
                </a>
                <a href="{{ route('site.impact.reports') }}" class="impact-path">
                    <span class="impact-path__num" aria-hidden="true">03</span>
                    <h3 class="impact-path__title">Impact reports</h3>
                    <p class="impact-path__desc">Annual reports and publications documenting progress and learning.</p>
                    <span class="impact-path__link">View reports <span aria-hidden="true">→</span></span>
                </a>
                <a href="{{ route('site.impact.regions') }}" class="impact-path">
                    <span class="impact-path__num" aria-hidden="true">04</span>
                    <h3 class="impact-path__title">Impact by region</h3>
                    <p class="impact-path__desc">How ASNEN's work reaches communities across Kenya and the wider continent.</p>
                    <span class="impact-path__link">See regions <span aria-hidden="true">→</span></span>
                </a>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Help carry this work forward"
        text="Membership, volunteering, partnership, and giving all strengthen inclusive education across Africa."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Support a programme', 'url' => route('site.get-involved.donate')]"
    />
@endsection
