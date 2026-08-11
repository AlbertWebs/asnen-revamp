@extends('layouts.public')

@section('title', 'Events & Learning | '.$siteName)
@section('meta_description', 'Conferences, workshops, and webinars advancing inclusive education across Africa.')

@section('content')
    <x-public.media-hero
        :show-parent="false"
        parent-label="Events & learning"
        eyebrow="Gather · Learn · Belong"
        title="Events & learning"
        title-max="14ch"
        tagline="Gather. Learn. Carry inclusion forward."
        :excerpt="$page?->excerpt ?? 'Conferences, workshops, and webinars that connect educators, caregivers, and advocates across Africa.'"
        :primary-cta="['label' => 'Upcoming events', 'url' => route('site.events.upcoming')]"
        :secondary-cta="['label' => 'Ubuntu Conference', 'url' => route('site.events.ubuntu-conference')]"
        :images="$bannerImages ?? []"
    />

    <x-public.events-subnav current="index" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Coming up</span>
                    <h2>Upcoming events</h2>
                    <p class="section-head-row__intro">Register early and join ASNEN in classrooms, communities, and online spaces.</p>
                </div>
                <a href="{{ route('site.events.upcoming') }}" class="btn-secondary section-head-row__cta">All upcoming</a>
            </div>

            @if($upcoming->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state
                        message="No upcoming events are scheduled right now."
                        :action="route('site.events.past')"
                        action-label="View past events"
                    />
                </div>
            @else
                <div class="events-home-grid reveal mt-8">
                    @foreach($upcoming as $event)
                        @include('public.events.partials.card', ['event' => $event])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Looking back</span>
                    <h2>Recent past events</h2>
                    <p class="section-head-row__intro">Reports, recordings, and learning from gatherings that shaped the network.</p>
                </div>
                <a href="{{ route('site.events.past') }}" class="btn-secondary section-head-row__cta">All past events</a>
            </div>

            @if($past->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state message="Past events will appear here once published." />
                </div>
            @else
                <div class="events-home-grid reveal mt-8">
                    @foreach($past as $event)
                        @include('public.events.partials.card', ['event' => $event])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Keep exploring</span>
                <h2>More ways to learn with ASNEN</h2>
                <p class="section-head-row__intro">Dive into webinars, the Ubuntu Conference series, and programme learning.</p>
            </div>

            <div class="who-explore reveal">
                <a href="{{ route('site.events.webinars') }}" class="who-explore__item">
                    <span class="who-explore__label">Webinars</span>
                    <span class="who-explore__desc">Online learning for caregivers, educators, and advocates</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.events.ubuntu-conference') }}" class="who-explore__item">
                    <span class="who-explore__label">Ubuntu Conference</span>
                    <span class="who-explore__desc">ASNEN's flagship gathering for inclusive education</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.resources.index') }}" class="who-explore__item">
                    <span class="who-explore__label">Resources</span>
                    <span class="who-explore__desc">Publications, toolkits, and recordings</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.get-involved.index') }}" class="who-explore__item">
                    <span class="who-explore__label">Get involved</span>
                    <span class="who-explore__desc">Walk with the network beyond a single event</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Join the next gathering"
        text="Membership keeps you connected to webinars, conferences, and learning across the ASNEN network."
        :primary-cta="['label' => 'Become a member', 'url' => route('site.get-involved.membership')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
