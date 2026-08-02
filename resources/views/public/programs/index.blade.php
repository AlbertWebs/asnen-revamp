@extends('layouts.public')

@section('title', ($page?->seoMeta?->title ?? 'What We Do').' | '.$siteName)
@section('meta_description', $page?->seoMeta?->description ?? $page?->excerpt ?? 'ASNEN programmes advancing inclusive education across Africa.')

@section('content')
    <x-public.about-hero
        current-label="What we do"
        title="What we do"
        title-max="11ch"
        tagline="Programmes that make inclusion real."
        :excerpt="$page?->excerpt ?? 'ASNEN advances inclusive education, caregiver support, advocacy, outreach, and partnerships across Africa.'"
        :primary-cta="['label' => 'Inclusive education', 'url' => route('site.programs.show', 'inclusive-education')]"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :show-visual="true"
    />

    <x-public.program-subnav :programs="$programs" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Our programmes</span>
                <h2>Homegrown models for belonging</h2>
                <p class="section-head-row__intro">Each programme moves knowledge, capacity, and care into homes, classrooms, and communities.</p>
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
