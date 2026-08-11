@extends('layouts.public')

@section('title', ($page->seoMeta?->title ?? $page->title).' | '.$siteName)
@section('meta_description', $page->seoMeta?->description ?? $page->excerpt)

@section('content')
    @php
        $milestones = [
            [
                'label' => 'Beginning',
                'title' => 'Community need',
                'body' => 'Educators, caregivers, and advocates recognised that children and young adults with disabilities deserve belonging, not exclusion.',
            ],
            [
                'label' => 'Growth',
                'title' => 'A living network',
                'body' => 'Collaborative action became programmes, outreach, webinars, and partnerships across Kenya and beyond.',
            ],
            [
                'label' => 'Today',
                'title' => 'Still being written',
                'body' => 'Classrooms, caregiver circles, medical camps, and policy forums continue to root inclusion in everyday life.',
            ],
        ];
    @endphp

    <x-public.media-hero
        parent-label="About"
        :parent-url="route('site.about.who-we-are')"
        current-label="Our story"
        eyebrow="Our journey"
        title="Our story"
        title-max="10ch"
        tagline="From shared need to shared work."
        :excerpt="$page->excerpt"
        :primary-cta="['label' => 'See our impact', 'url' => route('site.impact.overview')]"
        :secondary-cta="['label' => 'Meet the team', 'url' => route('site.about.leadership')]"
        :images="$bannerImages ?? []"
    />

    <x-public.about-subnav current="story" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">How we began</span>
                    <h2>Written with the communities we serve</h2>
                    @if(!empty($introHtml))
                        <div class="who-identity__body">
                            <x-public.prose :html="$sanitizer->clean($introHtml)" />
                        </div>
                    @endif
                </div>

                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">Throughline</p>
                    <p class="who-identity__aside-quote">Belonging is the measure of our work.</p>
                    <ul class="who-identity__aside-list">
                        <li>Classrooms and caregiver circles</li>
                        <li>Medical camps and outreach</li>
                        <li>Partnerships across Kenya and beyond</li>
                    </ul>
                    <a href="{{ route('site.impact.komolion') }}" class="who-identity__aside-link">
                        Read a field story
                        <span aria-hidden="true">→</span>
                    </a>
                </aside>
            </div>
        </div>
    </section>

    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">The arc</span>
                <h2>Where the story turns</h2>
                <p class="section-head-row__intro">ASNEN did not start as an institution first. It started as people refusing to leave children behind.</p>
            </div>

            <ol class="about-timeline reveal">
                @foreach($milestones as $index => $item)
                    <li class="about-timeline__item">
                        <span class="about-timeline__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <p class="about-timeline__label">{{ $item['label'] }}</p>
                        <h3 class="about-timeline__title">{{ $item['title'] }}</h3>
                        <p class="about-timeline__body">{{ $item['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <x-public.about-explore current="story" />

    <x-public.cta-band
        heading="Help write the next chapter"
        text="Join the network as a member, volunteer, partner, or supporter and carry inclusion forward."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
