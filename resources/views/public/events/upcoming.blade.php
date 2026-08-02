@extends('layouts.public')

@section('title', 'Upcoming Events | '.$siteName)
@section('meta_description', 'Register for upcoming ASNEN conferences, workshops, and learning events.')

@section('content')
    <x-public.about-hero
        breadcrumb="Events & learning"
        :breadcrumb-url="route('site.events.index')"
        current-label="Upcoming"
        title="Upcoming events"
        title-max="14ch"
        tagline="Save the date. Join the circle."
        excerpt="Upcoming conferences, workshops, and online sessions advancing inclusive education."
        :primary-cta="['label' => 'Past events', 'url' => route('site.events.past')]"
        :secondary-cta="['label' => 'Webinars', 'url' => route('site.events.webinars')]"
    />

    <x-public.events-subnav current="upcoming" />

    <section class="section-editorial">
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

    <x-public.cta-band
        heading="Want notice of the next event?"
        text="Join as a member or subscribe so you hear about webinars and conferences first."
        :primary-cta="['label' => 'Become a member', 'url' => route('site.get-involved.membership')]"
        :secondary-cta="['label' => 'Contact ASNEN', 'url' => route('site.contact')]"
    />
@endsection
