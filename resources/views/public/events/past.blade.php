@extends('layouts.public')

@section('title', 'Past Events | '.$siteName)
@section('meta_description', 'Explore past ASNEN conferences, workshops, and learning gatherings.')

@section('content')
    <x-public.about-hero
        breadcrumb="Events & learning"
        :breadcrumb-url="route('site.events.index')"
        current-label="Past"
        title="Past events"
        title-max="12ch"
        tagline="Learning we continue to carry."
        excerpt="Recaps and details from conferences, workshops, and sessions that shaped ASNEN's work."
        :primary-cta="['label' => 'Upcoming events', 'url' => route('site.events.upcoming')]"
        :secondary-cta="['label' => 'Ubuntu Conference', 'url' => route('site.events.ubuntu-conference')]"
    />

    <x-public.events-subnav current="past" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Archive</span>
                    <h2>Where we have gathered</h2>
                    <p class="section-head-row__intro">
                        @if($events->total() > 0)
                            {{ $events->total() }} past {{ \Illuminate\Support\Str::plural('event', $events->total()) }} from the ASNEN network.
                        @else
                            Past events will appear here once published.
                        @endif
                    </p>
                </div>
            </div>

            @if($events->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state message="No past events recorded yet." />
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
        heading="Be part of the next chapter"
        text="Upcoming webinars and conferences continue the conversations started here."
        :primary-cta="['label' => 'See upcoming', 'url' => route('site.events.upcoming')]"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
    />
@endsection
