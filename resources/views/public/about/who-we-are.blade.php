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
    @endphp

    <x-public.about-hero
        current-label="Who we are"
        brand="Demystifying Disability"
        title="Who we are"
        title-max="11ch"
        tagline="Inclusion for all, in all."
        :excerpt="$page->excerpt"
        :primary-cta="['label' => 'Vision, mission & values', 'url' => route('site.about.mission')]"
        :secondary-cta="['label' => 'Meet the team', 'url' => route('site.about.leadership')]"
        :images="[
            ['url' => asset('storage/galleries/community-moments/01.jpg'), 'alt' => 'ASNEN community gathering'],
            ['url' => asset('storage/galleries/baringo-2023/02.jpg'), 'alt' => 'Children and caregivers in Baringo'],
            ['url' => asset('storage/galleries/community-moments/03.jpg'), 'alt' => 'ASNEN partners at a community event'],
            ['url' => asset('storage/galleries/community-moments/05.jpg'), 'alt' => 'Families and advocates together'],
            ['url' => asset('storage/galleries/baringo-2023/04.jpg'), 'alt' => 'Community outreach in Baringo'],
            ['url' => asset('storage/galleries/community-moments/07.jpg'), 'alt' => 'ASNEN programme moment'],
        ]"
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
                    <a href="{{ route('site.about.story') }}" class="who-identity__aside-link">
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

    <x-public.about-explore current="who-we-are" />

    <x-public.ubuntu-values
        eyebrow="Grounded in Ubuntu"
        heading="Behaviours we practice together, not slogans we leave on a page."
    />

    <x-public.cta-band
        heading="Walk with ASNEN"
        text="Membership, volunteering, partnership, and giving all strengthen inclusive education across Africa."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
