@extends('layouts.public')

@section('title', $story->title.' | '.$siteName)
@section('meta_description', $story->summary)

@section('content')
    @php
        $slides = collect($story->gallery?->items ?? [])
            ->filter(fn ($item) => filled($item->mediaAsset?->publicUrl()))
            ->values()
            ->map(fn ($item) => [
                'src' => $item->mediaAsset->publicUrl(),
                'alt' => $item->mediaAsset->alt ?? $item->caption ?? $story->title,
                'caption' => $item->caption,
            ])
            ->all();

        $story->loadMissing(['featuredImage', 'partners.logo', 'programs']);
    @endphp

    <x-public.media-hero
        parent-label="Impact"
        :parent-url="route('site.impact.overview')"
        current-label="Success Stories"
        eyebrow="Case study · Baringo County"
        :title="$story->title"
        title-max="20ch"
        :excerpt="$story->summary"
        :primary-cta="['label' => 'All stories', 'url' => route('site.impact.stories')]"
        :secondary-cta="['label' => 'Impact overview', 'url' => route('site.impact.overview')]"
        :images="$bannerImages ?? []"
    />

    <x-public.impact-subnav current="stories" />

    @if($story->outcomes->isNotEmpty())
        <section class="case-outcomes" aria-label="Key outcomes">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="case-outcomes__head reveal">
                    <span class="eyebrow">Key outcomes</span>
                    <p class="case-outcomes__intro">Aggregate results from the 6 December 2023 disability assessment, NCPWD registration, and orthopedic medical camp.</p>
                </div>
                <div class="case-outcomes__grid reveal">
                    @foreach($story->outcomes as $outcome)
                        <div class="case-outcome">
                            <p class="case-outcome__value">{{ $outcome->value }}</p>
                            <p class="case-outcome__label">{{ $outcome->label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="case-study__layout reveal">
                @if($story->featuredImage)
                    <div class="case-study__media">
                        <x-public.media-frame
                            :asset="$story->featuredImage"
                            :alt="$story->featuredImage?->alt ?? $story->title"
                            ratio="4/3"
                            rounded="rounded-xl"
                            label="Case study photo"
                        />
                    </div>
                @endif

                <div class="case-study__body {{ $story->featuredImage ? '' : 'case-study__body--wide' }}">
                    <span class="eyebrow mb-3 block">The story</span>
                    <h2 class="case-study__body-title">What happened in Komolion</h2>
                    <x-public.prose :html="$sanitizer->clean($story->body)" class="mt-5" />
                </div>
            </div>
        </div>
    </section>

    <x-public.photo-gallery
        :slides="$slides"
        heading="From the camp"
        intro="Moments from the Komolion disability assessment, NCPWD registration, and orthopedic medical camp."
        eyebrow="Photo gallery"
    />

    @if($story->challenges || $story->learnings || $story->next_steps)
        <section class="section-editorial {{ count($slides) ? '' : 'bg-sand' }}">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head reveal">
                    <span class="eyebrow mb-3 block">Reflections</span>
                    <h2>What we learned</h2>
                    <p class="section-head-row__intro">Challenges, learning, and next steps from the camp - shared so others can build on the work.</p>
                </div>
                <div class="case-reflect-grid reveal">
                    @if($story->challenges)
                        <div class="case-reflect">
                            <h3>Challenges</h3>
                            <p>{{ $story->challenges }}</p>
                        </div>
                    @endif
                    @if($story->learnings)
                        <div class="case-reflect">
                            <h3>Learnings</h3>
                            <p>{{ $story->learnings }}</p>
                        </div>
                    @endif
                    @if($story->next_steps)
                        <div class="case-reflect">
                            <h3>Next steps</h3>
                            <p>{{ $story->next_steps }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    @if($story->partners->isNotEmpty())
        <section class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head reveal">
                    <span class="eyebrow mb-3 block">Collaboration</span>
                    <h2>Partners on the day</h2>
                    <p class="section-head-row__intro">Organisations that helped make the Komolion camp possible.</p>
                </div>
                <x-public.partner-logos :partners="$story->partners" layout="grid" />
            </div>
        </section>
    @endif

    <x-public.cta-band
        heading="Explore more impact"
        text="Read other stories and download reports from ASNEN programmes across the network."
        :primary-cta="['label' => 'Back to Impact', 'url' => route('site.impact.overview')]"
        :secondary-cta="['label' => 'Impact reports', 'url' => route('site.resources.publications')]"
    />
@endsection
