@extends('layouts.public')

@section('title', ($page->seoMeta?->title ?? $page->title).' | '.$siteName)
@section('meta_description', $page->seoMeta?->description ?? $page->excerpt)

@section('content')
    @php
        $page->loadMissing('blocks');
        $introHtml = $page->blocks->firstWhere('type', 'rich_text')?->content['body'] ?? null;
        $pillars = [
            [
                'title' => 'Knowledge',
                'body' => 'Share practical information families, educators, and communities can use.',
            ],
            [
                'title' => 'Capacity building',
                'body' => 'Strengthen caregivers, teachers, and partners to support inclusion with confidence.',
            ],
            [
                'title' => 'Advocacy',
                'body' => 'Champion dignity, belonging, and systems that leave no child behind.',
            ],
            [
                'title' => 'Collaboration',
                'body' => 'Connect schools, health partners, NGOs, and communities across Africa.',
            ],
            [
                'title' => 'Practical support',
                'body' => 'Deliver outreach, programmes, and tools that meet people where they are.',
            ],
            [
                'title' => 'Homegrown models',
                'body' => 'Advance African approaches rooted in Ubuntu, reciprocity, and local wisdom.',
            ],
        ];
        $heroImages = collect($bannerImages ?? []);
        if ($heroImages->isEmpty()) {
            $heroImages = collect([
                ['url' => asset('storage/galleries/community-moments/01.jpg'), 'alt' => 'ASNEN community gathering'],
                ['url' => asset('storage/galleries/baringo-2023/02.jpg'), 'alt' => 'Children and caregivers in Baringo'],
                ['url' => asset('storage/galleries/community-moments/03.jpg'), 'alt' => 'ASNEN partners at a community event'],
                ['url' => asset('storage/galleries/community-moments/05.jpg'), 'alt' => 'Families and advocates together'],
                ['url' => asset('storage/galleries/baringo-2023/04.jpg'), 'alt' => 'Community outreach in Baringo'],
                ['url' => asset('storage/galleries/community-moments/07.jpg'), 'alt' => 'ASNEN programme moment'],
            ]);
        }
    @endphp

    <x-public.media-hero
        parent-label="About"
        :parent-url="route('site.about.who-we-are')"
        current-label="Who we are"
        eyebrow="Demystifying Disability"
        title="Who we are"
        title-max="11ch"
        tagline="Inclusion for all, in all."
        :excerpt="$page->excerpt"
        :primary-cta="['label' => 'Meet the team', 'url' => route('site.about.leadership')]"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :images="$heroImages"
        fallback-image="storage/galleries/community-moments/01.jpg"
    />

    <x-public.about-subnav current="who-we-are" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">Our identity</span>
                    <h2>A pan-African coalition for belonging</h2>
                    @if($introHtml)
                        <div class="who-identity__body">
                            <x-public.prose :html="$sanitizer->clean($introHtml)" />
                        </div>
                    @endif
                </div>

                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">What we stand for</p>
                    <p class="who-identity__aside-quote">Inclusion is not an exception. It is an expectation.</p>
                    <ul class="who-identity__aside-list">
                        <li>Educators, caregivers, and advocates</li>
                        <li>Homegrown African models of support</li>
                        <li>Dignity across the lifespan</li>
                    </ul>
                    <a href="#story" class="who-identity__aside-link">
                        Read our story
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
                    <span class="eyebrow mb-3 block">How we work</span>
                    <h2>What ASNEN advances</h2>
                    <p class="section-head-row__intro">We develop and advance models that move knowledge, capacity, and care into homes, classrooms, and communities.</p>
                </div>
                <a href="{{ route('site.programs.index') }}" class="btn-secondary section-head-row__cta">Explore programmes</a>
            </div>

            <ol class="who-pillars reveal">
                @foreach($pillars as $index => $pillar)
                    <li class="who-pillar">
                        <span class="who-pillar__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="who-pillar__title">{{ $pillar['title'] }}</h3>
                        <p class="who-pillar__body">{{ $pillar['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    @php
        $sections = collect($missionSections ?? []);
        $vision = $sections->firstWhere('heading', 'Vision');
        $mission = $sections->firstWhere('heading', 'Mission');
        $philosophy = $sections->firstWhere('heading', 'Philosophy');
        $philosophyHtml = $philosophy['html'] ?? '<p>Ubuntu “I am because we are”, is not a tagline at ASNEN. It is the reason the work takes the shape it does. It is why we convene rather than compete, why we build peer circles rather than waiting lists, why caregivers become facilitators, and why we hold that inclusion belongs to everyone rather than to specialists alone. A person is a person through other people. The child who has been hidden is one of us. The mother who has carried this alone is one of us. We are because they are.</p>';
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

    <section id="vision" class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="about-mvv reveal">
                @if($vision)
                    <article class="about-mvv__panel about-mvv__panel--vision">
                        <span class="eyebrow mb-3 block">Vision</span>
                        <h2 class="about-mvv__title">The Africa we work toward</h2>
                        <div class="about-mvv__body">
                            <x-public.prose :html="$sanitizer->clean($vision['html'])" />
                        </div>
                    </article>
                @endif

                @if($mission)
                    <article id="mission" class="about-mvv__panel about-mvv__panel--mission">
                        <span class="eyebrow mb-3 block">Mission</span>
                        <h2 class="about-mvv__title">Why ASNEN exists</h2>
                        <div class="about-mvv__body">
                            <x-public.prose :html="$sanitizer->clean($mission['html'])" />
                        </div>
                    </article>
                @endif
            </div>
        </div>
    </section>

    <x-public.ubuntu-values
        eyebrow="Core Values"
        heading="Drawn from Ubuntu"
        intro="Our values are drawn from Ubuntu, the understanding that our humanity is bound to one another. They are written as behaviours rather than aspirations, so that members, partners and funders may hold us to them."
    />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Philosophy</span>
                <h2>Ubuntu is why the work takes this shape</h2>
            </div>
            <div class="reveal max-w-3xl">
                <x-public.prose :html="$sanitizer->clean($philosophyHtml)" />
            </div>
        </div>
    </section>

    <section id="story" class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">How we began</span>
                    <h2>Written with the communities we serve</h2>
                    @if(!empty($storyIntroHtml))
                        <div class="who-identity__body">
                            <x-public.prose :html="$sanitizer->clean($storyIntroHtml)" />
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
                    <a href="{{ route('site.impact.stories.show', \App\Models\ImpactStory::KOMOLION_SLUG) }}" class="who-identity__aside-link">
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

    <x-public.about-explore current="who-we-are" />

    <x-public.cta-band
        heading="Walk with ASNEN"
        text="Membership, volunteering, partnership, and giving all strengthen inclusive education across Africa."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
