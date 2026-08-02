@extends('layouts.public')

@section('title', $story->title.' | '.$siteName)

@section('content')
    <div class="mx-auto max-w-editorial px-4 py-12 sm:px-6 lg:px-8">
        <x-public.breadcrumbs :items="[
            ['label' => 'Impact', 'url' => route('site.impact.overview')],
            ['label' => 'Stories', 'url' => route('site.impact.stories')],
            ['label' => $story->title],
        ]" />
        <h1 class="font-display text-4xl font-bold text-forest">{{ $story->title }}</h1>
    </div>
    <x-public.section>
        <x-public.prose :html="$sanitizer->clean($story->body)" />
    </x-public.section>
@endsection
