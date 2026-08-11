@extends('layouts.public')

@section('title', ($page->seoMeta?->title ?? $page->title).' | '.$siteName)
@section('meta_description', $page->seoMeta?->description ?? $page->excerpt)

@section('content')
    @php
        $sections = collect($sections ?? []);
        $mission = $sections->firstWhere('heading', 'Mission');
        $vision = $sections->firstWhere('heading', 'Vision');
        $values = $sections->first(fn ($s) => in_array($s['heading'] ?? '', ['Core Values', 'Values'], true));
        $philosophy = $sections->firstWhere('heading', 'Philosophy');

        $coreValues = [
            [
                'term' => 'Utu',
                'gloss' => 'Dignity first',
                'body' => 'Utu is the Swahili expression of Ubuntu: humanness. We begin from the dignity of the child, not from the diagnosis. Disability describes a circumstance a person lives with. It never describes their worth, their capacity, or their claim on our respect.',
            ],
            [
                'term' => 'Belonging',
                'gloss' => 'I am because we are',
                'body' => 'Ubuntu holds that a person is a person through other people. We build circles rather than services, caregivers connected to caregivers, teachers to teachers, families to communities. Where a person has been isolated, our first act is to end the isolation.',
            ],
            [
                'term' => 'Harambee',
                'gloss' => 'Pulling together',
                'body' => 'Inclusion is not the specialist’s task, delegated and forgotten. It is the shared responsibility of families, schools, health workers, government and neighbours. We convene rather than compete, and we credit generously.',
            ],
            [
                'term' => 'Knowledge',
                'gloss' => 'Shared, not held',
                'body' => 'Expertise in disability has too often been guarded, held in clinics, in English, behind fees. We move knowledge in the opposite direction: outward, into homes and classrooms, in the languages people actually use. Demystification is an act of solidarity.',
            ],
            [
                'term' => 'Lived experience',
                'gloss' => 'Those who carry the work shape it',
                'body' => 'The mother who has raised a child with cerebral palsy for twelve years holds knowledge no training produces. We build leadership from lived experience, caregivers and persons with disabilities as facilitators, advocates and decision-makers, not as beneficiaries or case studies.',
            ],
            [
                'term' => 'Uwazi',
                'gloss' => 'Honest accounting',
                'body' => 'Ubuntu binds us to one another, and that binding requires truthfulness. We report accurately, claim only what we have done, correct ourselves publicly when we are wrong, and account transparently to members, partners and funders.',
            ],
        ];

        $philosophyHtml = $philosophy['html'] ?? '<p>Ubuntu “I am because we are”, is not a tagline at ASNEN. It is the reason the work takes the shape it does. It is why we convene rather than compete, why we build peer circles rather than waiting lists, why caregivers become facilitators, and why we hold that inclusion belongs to everyone rather than to specialists alone. A person is a person through other people. The child who has been hidden is one of us. The mother who has carried this alone is one of us. We are because they are.</p>';
    @endphp

    <x-public.media-hero
        parent-label="About"
        :parent-url="route('site.about.who-we-are')"
        current-label="Vision, mission & values"
        eyebrow="Our compass"
        title="Vision, mission & values"
        title-max="16ch"
        tagline="Grounded in Ubuntu."
        :excerpt="$page->excerpt"
        :primary-cta="['label' => 'Who we are', 'url' => route('site.about.who-we-are')]"
        :secondary-cta="['label' => 'Our story', 'url' => route('site.about.story')]"
        :images="$bannerImages ?? []"
    />

    <x-public.about-subnav current="mission" />

    <section class="section-editorial">
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
                    <article class="about-mvv__panel about-mvv__panel--mission">
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
        :values="$coreValues"
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

    <x-public.about-explore current="mission" />

    <x-public.cta-band
        heading="Walk with ASNEN"
        text="Membership, volunteering, partnership, and giving all strengthen inclusive education across Africa."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
