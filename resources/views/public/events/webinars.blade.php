@extends('layouts.public')

@section('title', 'Webinars | '.$siteName)
@section('meta_description', 'ASNEN webinars connecting caregivers, educators, and advocates across Africa.')

@section('content')
    <x-public.about-hero
        breadcrumb="Events & learning"
        :breadcrumb-url="route('site.events.index')"
        current-label="Webinars"
        title="Webinars"
        title-max="10ch"
        tagline="Learning without borders."
        excerpt="Online sessions that move knowledge into homes, classrooms, and community practice."
        :primary-cta="['label' => 'Upcoming events', 'url' => route('site.events.upcoming')]"
        :secondary-cta="['label' => 'Resources library', 'url' => route('site.resources.webinars')]"
    />

    <x-public.events-subnav current="webinars" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Online learning</span>
                    <h2>Published webinars</h2>
                    <p class="section-head-row__intro">
                        @if($webinars->total() > 0)
                            {{ $webinars->total() }} webinar {{ \Illuminate\Support\Str::plural('session', $webinars->total()) }} from the ASNEN network.
                        @else
                            Webinar sessions will appear here once published.
                        @endif
                    </p>
                </div>
            </div>

            @if($webinars->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state
                        message="Webinars will appear here once published."
                        :action="route('site.events.index')"
                        action-label="Back to events"
                    />
                </div>
            @else
                <div class="webinar-grid reveal mt-8">
                    @foreach($webinars as $webinar)
                        <article class="webinar-card">
                            <div class="webinar-card__meta">
                                <span class="webinar-card__type">Webinar</span>
                                @if($webinar->held_at)
                                    <span class="webinar-card__date">{{ $webinar->held_at->format('F Y') }}</span>
                                @endif
                            </div>
                            <h3 class="webinar-card__title">{{ $webinar->title }}</h3>
                            @if($webinar->moderator)
                                <p class="webinar-card__moderator">With {{ $webinar->moderator }}</p>
                            @endif
                            @if($webinar->summary)
                                <p class="webinar-card__summary">{{ $webinar->summary }}</p>
                            @endif
                            <div class="webinar-card__footer">
                                @if($webinar->participant_count)
                                    <span class="webinar-card__stat">{{ number_format($webinar->participant_count) }} participants</span>
                                @endif
                                @if($webinar->recording_url)
                                    <a href="{{ $webinar->recording_url }}" class="webinar-card__link" target="_blank" rel="noopener noreferrer">
                                        Watch recording
                                        <span aria-hidden="true">→</span>
                                    </a>
                                @else
                                    <span class="webinar-card__link webinar-card__link--muted">Recording coming soon</span>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="reveal">
                    <x-public.pagination :paginator="$webinars" />
                </div>
            @endif
        </div>
    </section>

    <x-public.cta-band
        heading="Stay in the learning circle"
        text="Membership and volunteering keep you close to ASNEN webinars and gatherings."
        :primary-cta="['label' => 'Become a member', 'url' => route('site.get-involved.membership')]"
        :secondary-cta="['label' => 'Volunteer', 'url' => route('site.get-involved.volunteer')]"
    />
@endsection
