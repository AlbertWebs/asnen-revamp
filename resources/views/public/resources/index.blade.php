@extends('layouts.public')

@section('title', 'Resources | '.$siteName)
@section('meta_description', $page?->excerpt ?? 'Reports, toolkits, webinar recordings, news, and gallery from ASNEN.')

@section('content')
    <x-public.media-hero
        :show-parent="false"
        parent-label="Resources"
        eyebrow="Library"
        :title="$page?->title ?? 'Resources'"
        title-max="12ch"
        tagline="Knowledge for practice."
        :excerpt="$page?->excerpt ?? 'Reports, toolkits, webinar recordings, news, and gallery from ASNEN.'"
        :primary-cta="['label' => 'Reports & publications', 'url' => route('site.resources.publications')]"
        :secondary-cta="['label' => 'Toolkits & guides', 'url' => route('site.resources.toolkits')]"
        :images="$bannerImages ?? []"
    />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Browse</span>
                <h2>Find what you need</h2>
                <p class="section-head-row__intro">Practical materials and updates from the ASNEN network.</p>
            </div>
            <div class="reveal mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                <a href="{{ route('site.resources.publications') }}" class="rounded-lg border border-sand bg-white p-5 transition hover:border-teal">Reports & Publications</a>
                <a href="{{ route('site.resources.toolkits') }}" class="rounded-lg border border-sand bg-white p-5 transition hover:border-teal">Toolkits & Guides</a>
                <a href="{{ route('site.resources.webinars') }}" class="rounded-lg border border-sand bg-white p-5 transition hover:border-teal">Webinar Library</a>
                <a href="{{ route('site.resources.news') }}" class="rounded-lg border border-sand bg-white p-5 transition hover:border-teal">News & Insights</a>
                <a href="{{ route('site.resources.gallery.index') }}" class="rounded-lg border border-sand bg-white p-5 transition hover:border-teal">Gallery</a>
            </div>
        </div>
    </section>
@endsection
