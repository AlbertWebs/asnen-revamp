@extends('layouts.public')

@section('title', 'Toolkits & Guides | '.$siteName)
@section('meta_description', 'Practical ASNEN toolkits and guides for educators, caregivers, and community partners advancing inclusive education.')

@section('content')
    @php
        $uses = [
            [
                'title' => 'In classrooms',
                'body' => 'Practical prompts for welcoming every learner with dignity and high expectations.',
            ],
            [
                'title' => 'With caregivers',
                'body' => 'Simple tools families can use at home and in everyday routines.',
            ],
            [
                'title' => 'Across communities',
                'body' => 'Shared language for schools, health partners, and advocates working together.',
            ],
            [
                'title' => 'For facilitators',
                'body' => 'Sessionable guides that help ASNEN partners run workshops with confidence.',
            ],
        ];
    @endphp

    <x-public.about-hero
        breadcrumb="Resources"
        :breadcrumb-url="route('site.resources.index')"
        current-label="Toolkits"
        title="Toolkits & guides"
        title-max="14ch"
        tagline="Knowledge you can use today."
        excerpt="Practical guides for educators, caregivers, and partners building inclusive education across Africa."
        :primary-cta="['label' => 'All publications', 'url' => route('site.resources.publications')]"
        :secondary-cta="['label' => 'Webinar library', 'url' => route('site.resources.webinars')]"
    />

    <x-public.resources-subnav current="toolkits" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">Why toolkits</span>
                    <h2>Made for practice, not the shelf</h2>
                    <div class="who-identity__body">
                        <p class="text-lg leading-relaxed text-charcoal-500">
                            ASNEN toolkits turn programme learning into steps people can take in classrooms, homes, and community spaces. Each guide is published once it is verified and ready to share.
                        </p>
                    </div>
                </div>
                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">Who they serve</p>
                    <p class="who-identity__aside-quote">Teachers, caregivers, and partners walking with children.</p>
                    <ul class="who-identity__aside-list">
                        <li>Clear language, honest scope</li>
                        <li>Rooted in Ubuntu and dignity</li>
                        <li>Updated as practice grows</li>
                    </ul>
                    <a href="{{ route('site.programs.index') }}" class="who-identity__aside-link">
                        Explore programmes
                        <span aria-hidden="true">→</span>
                    </a>
                </aside>
            </div>
        </div>
    </section>

    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">Library</span>
                    <h2>Guides you can download</h2>
                    <p class="section-head-row__intro">
                        @if($publications->total() > 0)
                            {{ $publications->total() }} {{ \Illuminate\Support\Str::plural('toolkit', $publications->total()) }} published for the ASNEN network.
                        @else
                            Toolkits will appear here once uploaded and verified in the admin panel.
                        @endif
                    </p>
                </div>
                <a href="{{ route('site.resources.publications') }}" class="btn-secondary section-head-row__cta">All publications</a>
            </div>

            <div class="reveal mt-8">
                @if($publications->isEmpty())
                    <x-public.empty-state
                        message="Toolkits and guides will be published here."
                        :action="route('site.contact')"
                        action-label="Ask about available materials"
                    />
                @else
                    <div class="report-grid">
                        @foreach($publications as $publication)
                            <article class="report-card">
                                <div class="report-card__cover">
                                    <x-public.media-frame
                                        :asset="$publication->cover"
                                        :alt="$publication->cover?->alt ?? $publication->title"
                                        ratio="3/4"
                                        rounded="rounded-none"
                                        label="Toolkit cover"
                                    />
                                </div>
                                <div class="report-card__body">
                                    <div class="report-card__meta">
                                        <span class="report-card__type">{{ $publication->categoryLabel() }}</span>
                                        @if($publication->year)
                                            <span class="report-card__year">{{ $publication->year }}</span>
                                        @endif
                                        @if($publication->version)
                                            <span class="report-card__year">v{{ $publication->version }}</span>
                                        @endif
                                    </div>
                                    <h2 class="report-card__title">
                                        <a href="{{ route('site.resources.publications.show', $publication->slug) }}">{{ $publication->title }}</a>
                                    </h2>
                                    @if($publication->abstract)
                                        <p class="report-card__summary">{{ $publication->abstract }}</p>
                                    @endif
                                    <div class="report-card__actions">
                                        @if($publication->file)
                                            <a href="{{ route('site.resources.publications.download', $publication->slug) }}" class="btn-primary report-card__download">
                                                Download PDF
                                                @if($publication->fileSizeLabel())
                                                    <span class="report-card__size">{{ $publication->fileSizeLabel() }}</span>
                                                @endif
                                            </a>
                                        @else
                                            <span class="report-card__unavailable">PDF coming soon</span>
                                        @endif
                                        <a href="{{ route('site.resources.publications.show', $publication->slug) }}" class="report-card__details">
                                            View details
                                            <span aria-hidden="true">→</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <x-public.pagination :paginator="$publications" />
                @endif
            </div>
        </div>
    </section>

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">How people use them</span>
                <h2>From page to practice</h2>
                <p class="section-head-row__intro">Toolkits are written so they travel - into schools, caregiver circles, and partner workshops.</p>
            </div>

            <ol class="who-pillars reveal">
                @foreach($uses as $index => $item)
                    <li class="who-pillar">
                        <span class="who-pillar__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="who-pillar__title">{{ $item['title'] }}</h3>
                        <p class="who-pillar__body">{{ $item['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Keep exploring</span>
                <h2>More from the resource library</h2>
                <p class="section-head-row__intro">Pair toolkits with reports, webinars, and programme pages.</p>
            </div>

            <div class="who-explore reveal">
                <a href="{{ route('site.resources.publications') }}" class="who-explore__item">
                    <span class="who-explore__label">Reports &amp; publications</span>
                    <span class="who-explore__desc">Conference and programme documents</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.resources.webinars') }}" class="who-explore__item">
                    <span class="who-explore__label">Webinar library</span>
                    <span class="who-explore__desc">Recorded learning sessions</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.programs.show', 'inclusive-education') }}" class="who-explore__item">
                    <span class="who-explore__label">Inclusive education</span>
                    <span class="who-explore__desc">Programme context behind the guides</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.get-involved.partner') }}" class="who-explore__item">
                    <span class="who-explore__label">Partner with ASNEN</span>
                    <span class="who-explore__desc">Co-create materials for your community</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Need a toolkit for your school or partner?"
        text="Tell ASNEN what you need. We can share published guides or explore collaboration on new materials."
        :primary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
        :secondary-cta="['label' => 'Partner with us', 'url' => route('site.get-involved.partner')]"
    />
@endsection
