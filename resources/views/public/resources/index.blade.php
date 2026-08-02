@extends('layouts.public')

@section('title', 'Resources | '.$siteName)

@section('content')
    <div class="mx-auto max-w-editorial px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="font-display text-4xl font-bold text-forest">Resources</h1>
        <p class="mt-4 max-w-3xl text-charcoal/80">Reports, toolkits, webinar recordings, news, and gallery from ASNEN.</p>
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            <a href="{{ route('site.resources.publications') }}" class="rounded-lg border border-sand p-5 hover:border-teal">Reports & Publications</a>
            <a href="{{ route('site.resources.toolkits') }}" class="rounded-lg border border-sand p-5 hover:border-teal">Toolkits & Guides</a>
            <a href="{{ route('site.resources.webinars') }}" class="rounded-lg border border-sand p-5 hover:border-teal">Webinar Library</a>
            <a href="{{ route('site.resources.news') }}" class="rounded-lg border border-sand p-5 hover:border-teal">News & Insights</a>
            <a href="{{ route('site.resources.gallery.index') }}" class="rounded-lg border border-sand p-5 hover:border-teal">Gallery</a>
        </div>
    </div>
@endsection
