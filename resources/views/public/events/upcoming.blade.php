@extends('layouts.public')

@section('title', 'Upcoming Events | '.$siteName)
@section('meta_description', 'ASNEN’s Pre-Registration Webinar on 23 November 2026 and Disability Registration Day on 5 December 2026. Partner with NCPWD and the Ministry of Health to reach 500 households.')

@section('content')
    @php
        $initiative = config('event_pages.initiative', []);
    @endphp

    <x-public.media-hero
        parent-label="Events & learning"
        :parent-url="route('site.events.index')"
        current-label="Upcoming"
        eyebrow="Partner with us"
        title="Upcoming events"
        title-max="14ch"
        :tagline="$initiative['tagline'] ?? 'Inclusion for all, in all. No child left behind.'"
        :excerpt="$initiative['season_line'] ?? 'Upcoming conferences, workshops, and online sessions advancing inclusive education.'"
        :primary-cta="['label' => 'Partner with us', 'url' => '#partner-with-this-initiative']"
        :secondary-cta="['label' => 'Registration Day', 'url' => route('site.events.show', 'disability-registration-day-2026')]"
        :images="$bannerImages ?? []"
    />

    <x-public.events-subnav current="upcoming" />

    @if(($ongoing ?? collect())->isNotEmpty())
        <section class="section-editorial" aria-labelledby="ongoing-events-heading">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">Happening now</span>
                        <h2 id="ongoing-events-heading">Ongoing events</h2>
                        <p class="section-head-row__intro">{{ $ongoing->count() }} {{ \Illuminate\Support\Str::plural('engagement', $ongoing->count()) }} currently underway.</p>
                    </div>
                </div>
                <div class="events-home-grid reveal mt-8">
                    @foreach($ongoing as $event)
                        @include('public.events.partials.card', ['event' => $event])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section-editorial {{ ($ongoing ?? collect())->isNotEmpty() ? 'bg-sand' : '' }}">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Schedule</span>
                    <h2>What is coming next</h2>
                    <p class="section-head-row__intro">
                        @if($events->total() > 0)
                            {{ $events->total() }} upcoming {{ \Illuminate\Support\Str::plural('event', $events->total()) }}.
                        @else
                            New dates will appear here as soon as they are published.
                        @endif
                    </p>
                </div>
            </div>

            @if($events->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state
                        message="No upcoming events at this time."
                        :action="route('site.events.past')"
                        action-label="Browse past events"
                    />
                </div>
            @else
                <div class="events-home-grid reveal mt-8">
                    @foreach($events as $event)
                        @include('public.events.partials.card', ['event' => $event])
                    @endforeach
                </div>
                <div class="reveal">
                    <x-public.pagination :paginator="$events" />
                </div>
            @endif
        </div>
    </section>

    @include('public.events.partials.registration-partner-ask', ['showSeasonIntro' => true])

    <x-public.cta-band
        heading="Walk with Us"
        text="Membership, volunteering, and giving also strengthen inclusive education across the network."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact ASNEN', 'url' => route('site.contact')]"
    />
@endsection
