@extends('layouts.public')

@section('title', 'Ubuntu Conference | '.$siteName)
@section('meta_description', 'ASNEN\'s Ubuntu Conference series convening African models of inclusive education.')

@section('content')
    @php
        $featured = $event;
        $series = $ubuntuEvents ?? collect();
    @endphp

    <x-public.media-hero
        parent-label="Events & learning"
        :parent-url="route('site.events.index')"
        current-label="Ubuntu Conference"
        eyebrow="Flagship gathering"
        title="Ubuntu Conference"
        title-max="14ch"
        tagline="I am because we are."
        :excerpt="$page?->excerpt ?? 'ASNEN\'s flagship gathering for inclusive education - rooted in Ubuntu, African wisdom, and shared practice.'"
        :primary-cta="$featured ? ['label' => 'Latest conference', 'url' => route('site.events.show', $featured->slug)] : ['label' => 'Past events', 'url' => route('site.events.past')]"
        :secondary-cta="['label' => 'Impact reports', 'url' => route('site.impact.reports')]"
        :images="$bannerImages ?? []"
    />

    <x-public.events-subnav current="ubuntu" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">The gathering</span>
                    <h2>A conference shaped by belonging</h2>
                    @if($featured?->body || $featured?->summary)
                        <div class="who-identity__body">
                            <x-public.prose :html="$sanitizer->clean($featured->body ?? $featured->summary)" />
                        </div>
                    @else
                        <div class="who-identity__body">
                            <p class="text-lg leading-relaxed text-charcoal-500">
                                The Ubuntu Conference brings educators, caregivers, advocates, and partners together to advance African, homegrown models of inclusive education. Each gathering deepens practice, evidence, and community.
                            </p>
                        </div>
                    @endif
                    @if($featured)
                        <div class="mt-6">
                            <a href="{{ route('site.events.show', $featured->slug) }}" class="btn-primary">Open conference details</a>
                        </div>
                    @endif
                </div>

                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">What Ubuntu means here</p>
                    <p class="who-identity__aside-quote">Inclusion is shared work.</p>
                    <ul class="who-identity__aside-list">
                        <li>African models of practice</li>
                        <li>Lived experience at the centre</li>
                        <li>Learning that continues after the hall</li>
                    </ul>
                    <a href="{{ route('site.impact.reports') }}" class="who-identity__aside-link">
                        Download reports
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
                    <span class="eyebrow mb-3 block">The series</span>
                    <h2>Ubuntu Conference editions</h2>
                    <p class="section-head-row__intro">Each edition leaves a trail of reports, relationships, and renewed commitment.</p>
                </div>
            </div>

            @if($series->isEmpty())
                <div class="reveal mt-8">
                    <x-public.empty-state message="Ubuntu Conference details will be published here." />
                </div>
            @else
                <div class="events-home-grid reveal mt-8">
                    @foreach($series as $conference)
                        @include('public.events.partials.card', ['event' => $conference])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <x-public.ubuntu-values
        eyebrow="Grounded in Ubuntu"
        heading="The values that shape every Ubuntu Conference."
    />

    <x-public.cta-band
        heading="Walk with the next Ubuntu Conference"
        text="Partner, volunteer, or join as a member to help host the next gathering."
        :primary-cta="['label' => 'Partner with us', 'url' => route('site.get-involved.partner')]"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
    />
@endsection
