@extends('layouts.public')

@section('title', 'Webinar Library | '.$siteName)
@section('meta_description', 'Watch ASNEN webinar recordings and revisit learning for educators, caregivers, and advocates.')

@section('content')
    @php
        $audiences = [
            [
                'title' => 'Educators',
                'body' => 'Classroom practice, inclusion strategies, and peer learning you can take back to school.',
            ],
            [
                'title' => 'Caregivers',
                'body' => 'Practical guidance for supporting children and young adults at home and in community life.',
            ],
            [
                'title' => 'Advocates',
                'body' => 'Rights-based conversations that centre lived experience and shared responsibility.',
            ],
            [
                'title' => 'Partners',
                'body' => 'Programme insights that help schools, NGOs, and health actors pull together.',
            ],
        ];

        $featured = $webinars->getCollection()->first(fn ($item) => filled($item->recording_url))
            ?? $webinars->getCollection()->first();
    @endphp

    <x-public.media-hero
        parent-label="Resources"
        :parent-url="route('site.resources.index')"
        current-label="Webinar library"
        eyebrow="Recorded learning"
        title="Webinar library"
        title-max="14ch"
        tagline="Learning you can return to."
        :excerpt="$page?->excerpt ?? 'Recorded ASNEN sessions for educators, caregivers, and advocates building inclusive education across Africa.'"
        :primary-cta="['label' => 'Upcoming events', 'url' => route('site.events.upcoming')]"
        :secondary-cta="['label' => 'Toolkits & guides', 'url' => route('site.resources.toolkits')]"
        :images="$bannerImages ?? []"
    />

    <x-public.resources-subnav current="webinars" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">Why this library</span>
                    <h2>Sessions that keep teaching after the day ends</h2>
                    <div class="who-identity__body">
                        <p class="text-lg leading-relaxed text-charcoal-500">
                            ASNEN webinars move knowledge outward - into homes, classrooms, and community practice. This library gathers published recordings and session notes so learning stays available across the network.
                        </p>
                    </div>
                </div>
                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">What you will find</p>
                    <p class="who-identity__aside-quote">Recordings, summaries, and pathways back into programmes.</p>
                    <ul class="who-identity__aside-list">
                        <li>Verified, published sessions only</li>
                        <li>Clear dates and facilitators</li>
                        <li>Links to related tools and events</li>
                    </ul>
                    <a href="{{ route('site.events.webinars') }}" class="who-identity__aside-link">
                        Events webinars page
                        <span aria-hidden="true">→</span>
                    </a>
                </aside>
            </div>
        </div>
    </section>

    @if($featured)
        <section class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">Start here</span>
                        <h2>Featured session</h2>
                        <p class="section-head-row__intro">A recent webinar from the ASNEN learning circle.</p>
                    </div>
                </div>

                <article class="webinar-feature reveal">
                    <div class="webinar-feature__media">
                        <x-public.media-frame
                            :asset="$featured->featuredImage"
                            :alt="$featured->featuredImage?->alt ?? $featured->title"
                            ratio="16/9"
                            rounded="rounded-2xl"
                            label="Webinar image"
                        />
                    </div>
                    <div class="webinar-feature__copy">
                        <div class="webinar-card__meta">
                            <span class="webinar-card__type">Webinar</span>
                            @if($featured->held_at)
                                <span class="webinar-card__date">{{ $featured->held_at->format('F Y') }}</span>
                            @endif
                        </div>
                        <h3 class="webinar-feature__title">{{ $featured->title }}</h3>
                        @if($featured->moderator)
                            <p class="webinar-card__moderator">With {{ $featured->moderator }}</p>
                        @endif
                        @if($featured->summary)
                            <p class="webinar-feature__summary">{{ $featured->summary }}</p>
                        @endif
                        <div class="webinar-feature__actions">
                            @if($featured->recording_url)
                                <a href="{{ $featured->recording_url }}" class="btn-primary" target="_blank" rel="noopener noreferrer">Watch recording</a>
                            @endif
                            @if($featured->participant_count)
                                <span class="webinar-card__stat">{{ number_format($featured->participant_count) }} participants</span>
                            @endif
                        </div>
                    </div>
                </article>
            </div>
        </section>
    @endif

    <section class="section-editorial {{ $featured ? '' : 'bg-sand' }}">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">All sessions</span>
                    <h2>Published webinar library</h2>
                    <p class="section-head-row__intro">
                        @if($webinars->total() > 0)
                            {{ $webinars->total() }} webinar {{ \Illuminate\Support\Str::plural('session', $webinars->total()) }} available to the network.
                        @else
                            Webinar recordings will appear here once published.
                        @endif
                    </p>
                </div>
                <a href="{{ route('site.events.upcoming') }}" class="btn-secondary section-head-row__cta">Upcoming events</a>
            </div>

            @if($webinars->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state
                        message="Webinar recordings will appear here."
                        :action="route('site.events.index')"
                        action-label="Browse events"
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

    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Who they serve</span>
                <h2>Learning for every part of the circle</h2>
                <p class="section-head-row__intro">Sessions are designed so different people in a child's world can learn together.</p>
            </div>

            <ol class="who-pillars reveal">
                @foreach($audiences as $index => $item)
                    <li class="who-pillar">
                        <span class="who-pillar__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="who-pillar__title">{{ $item['title'] }}</h3>
                        <p class="who-pillar__body">{{ $item['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Keep exploring</span>
                <h2>Continue from this library</h2>
                <p class="section-head-row__intro">Pair recordings with toolkits, events, and programme pages.</p>
            </div>

            <div class="who-explore reveal">
                <a href="{{ route('site.resources.toolkits') }}" class="who-explore__item">
                    <span class="who-explore__label">Toolkits &amp; guides</span>
                    <span class="who-explore__desc">Practical materials to use after watching</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.events.upcoming') }}" class="who-explore__item">
                    <span class="who-explore__label">Upcoming events</span>
                    <span class="who-explore__desc">Join the next live session</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.resources.publications') }}" class="who-explore__item">
                    <span class="who-explore__label">Publications</span>
                    <span class="who-explore__desc">Reports and conference documents</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.get-involved.membership') }}" class="who-explore__item">
                    <span class="who-explore__label">Become a member</span>
                    <span class="who-explore__desc">Stay close to ASNEN learning</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Join the next live conversation"
        text="Membership keeps you connected to webinars, conferences, and learning across the ASNEN network."
        :primary-cta="['label' => 'Become a member', 'url' => route('site.get-involved.membership')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
