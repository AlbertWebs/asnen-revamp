@extends('layouts.public')

@section('title', $story->title.' | '.$siteName)
@section('meta_description', $story->summary ?? $story->title)

@section('content')
    <x-public.media-hero
        parent-label="Impact"
        :parent-url="route('site.impact.overview')"
        current-label="Story"
        eyebrow="Impact story"
        :title="$story->title"
        title-max="18ch"
        :excerpt="$story->summary"
        :primary-cta="['label' => 'All stories', 'url' => route('site.impact.stories')]"
        :secondary-cta="['label' => 'Impact overview', 'url' => route('site.impact.overview')]"
        :images="$bannerImages ?? []"
    />

    <x-public.section>
        <x-public.prose :html="$sanitizer->clean($story->body)" />
    </x-public.section>
@endsection
