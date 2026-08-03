@extends('layouts.public')

@section('title', ($page->seoMeta?->title ?? $page->title).' | '.$siteName)
@section('meta_description', $page->seoMeta?->description ?? $page->excerpt)

@section('content')
    @php
        $sections = $sections ?? [];
        $mission = collect($sections)->firstWhere('heading', 'Mission');
        $vision = collect($sections)->firstWhere('heading', 'Vision');
        $values = collect($sections)->firstWhere('heading', 'Values');
    @endphp

    <x-public.about-hero
        current-label="Mission & values"
        title="Mission, vision & values"
        title-max="16ch"
        tagline="Grounded in Ubuntu."
        :excerpt="$page->excerpt"
        :primary-cta="['label' => 'Who we are', 'url' => route('site.about.who-we-are')]"
        :secondary-cta="['label' => 'Our story', 'url' => route('site.about.story')]"
        :show-visual="true"
    />

    <x-public.about-subnav current="mission" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="about-mvv reveal">
                @if($mission)
                    <article class="about-mvv__panel about-mvv__panel--mission">
                        <span class="eyebrow mb-3 block">Mission</span>
                        <h2 class="about-mvv__title">Why ASNEN exists</h2>
                        <div class="about-mvv__body">
                            <x-public.prose :html="$sanitizer->clean($mission['html'])" />
                        </div>
                    </article>
                @endif

                @if($vision)
                    <article class="about-mvv__panel about-mvv__panel--vision">
                        <span class="eyebrow mb-3 block">Vision</span>
                        <h2 class="about-mvv__title">The Africa we work toward</h2>
                        <div class="about-mvv__body">
                            <x-public.prose :html="$sanitizer->clean($vision['html'])" />
                        </div>
                    </article>
                @endif
            </div>
        </div>
    </section>

    @if($values)
        <section class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head reveal">
                    <span class="eyebrow mb-3 block">Values</span>
                    <h2>Commitments we practise</h2>
                    <p class="section-head-row__intro">These are not slogans. They shape how ASNEN teaches, partners, reports, and tells stories.</p>
                </div>

                <div class="about-values reveal">
                    <x-public.prose :html="$sanitizer->clean($values['html'])" class="about-values__prose" />
                </div>
            </div>
        </section>
    @elseif(!empty($introHtml))
        <section class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="reveal max-w-3xl">
                    <x-public.prose :html="$sanitizer->clean($introHtml)" />
                </div>
            </div>
        </section>
    @endif

    <x-public.ubuntu-values
        eyebrow="Ubuntu in practice"
        heading="Written as behaviours, not aspirations — so members and partners can hold us to them."
    />

    <x-public.about-explore current="mission" />

    <x-public.cta-band
        heading="Walk with ASNEN"
        text="Membership, volunteering, partnership, and giving all strengthen inclusive education across Africa."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
