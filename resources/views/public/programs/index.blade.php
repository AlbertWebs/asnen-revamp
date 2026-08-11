@extends('layouts.public')

@section('title', ($page?->seoMeta?->title ?? 'What We Do | Inclusive Education Programmes').' | '.$siteName)
@section('meta_description', $page?->seoMeta?->description ?? $page?->excerpt ?? 'ASNEN advances inclusion through seven interconnected programme areas, moving knowledge, capacity and care into homes, classrooms and communities.')

@section('content')
    <x-public.media-hero
        :show-parent="false"
        parent-label="What we do"
        eyebrow="Our programmes"
        title="What we do"
        title-max="11ch"
        tagline="Seven interconnected programme areas."
        :excerpt="$page?->excerpt ?? 'ASNEN advances inclusion through seven interconnected programme areas, moving knowledge, capacity and care into homes, classrooms and communities.'"
        :primary-cta="['label' => 'Inclusive education', 'url' => route('site.programs.show', 'inclusive-education')]"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :images="$bannerImages ?? []"
    />

    <x-public.program-subnav :programs="$programs" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Our programmes</span>
                <h2>Seven interconnected programme areas</h2>
                <p class="section-head-row__intro">ASNEN advances inclusion through seven interconnected programme areas, moving knowledge, capacity and care into homes, classrooms and communities.</p>
            </div>

            <div class="reveal mt-8">
                <x-public.program-pillars :programs="$programs" />
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Walk with ASNEN"
        text="Membership, volunteering, partnership, and giving all strengthen these programmes."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
