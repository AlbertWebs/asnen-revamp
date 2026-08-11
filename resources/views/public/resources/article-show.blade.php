@extends('layouts.public')

@section('title', $article->title.' | '.$siteName)
@section('meta_description', $article->excerpt ?? ($article->title.' - ASNEN news and insights.'))

@section('content')
    @php
        $meta = collect([
            $article->category ? \Illuminate\Support\Str::headline($article->category) : 'Insight',
            $article->published_at?->format('j F Y'),
            $article->reading_time_minutes ? $article->reading_time_minutes.' min read' : null,
        ])->filter()->implode(' · ');
    @endphp

    <x-public.media-hero
        parent-label="News"
        :parent-url="route('site.resources.news')"
        current-label="Article"
        eyebrow="News & insights"
        :title="$article->title"
        title-max="18ch"
        :tagline="$meta"
        :excerpt="$article->excerpt"
        :primary-cta="['label' => 'All news', 'url' => route('site.resources.news')]"
        :secondary-cta="['label' => 'Impact stories', 'url' => route('site.impact.stories')]"
        :images="$bannerImages ?? []"
    />

    <x-public.resources-subnav current="news" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="news-article reveal">
                @if($article->featuredImage)
                    <div class="news-article__media">
                        <x-public.media-frame
                            :asset="$article->featuredImage"
                            :alt="$article->featuredImage?->alt ?? $article->title"
                            ratio="16/9"
                            rounded="rounded-2xl"
                            label="Article image"
                        />
                    </div>
                @endif

                <div class="news-article__body">
                    <x-public.prose :html="$sanitizer->clean($article->body)" />
                </div>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Continue exploring"
        text="Read more ASNEN insights, or follow the evidence through impact stories and learning."
        :primary-cta="['label' => 'More news', 'url' => route('site.resources.news')]"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
    />
@endsection
