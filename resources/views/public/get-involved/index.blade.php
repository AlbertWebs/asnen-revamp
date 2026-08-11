@extends('layouts.public')

@section('title', ($page->seoMeta?->title ?? $page->title).' | '.$siteName)
@section('meta_description', $page->seoMeta?->description ?? $page->excerpt)

@section('content')
    @php
        $page->loadMissing('blocks');
        $introHtml = $page->blocks->firstWhere('type', 'rich_text')?->content['body'] ?? null;
        $contentBlocks = $page->blocks->reject(fn ($b) => $b->type === 'rich_text');
    @endphp

    <x-public.media-hero
        :title="$page->title ?? 'Get Involved'"
        title-max="12ch"
        heading-id="get-involved-hero-heading"
        eyebrow="Walk with ASNEN"
        :excerpt="$page->excerpt ?? 'Join ASNEN as a member, volunteer, partner, or supporter.'"
        :body-html="$introHtml ? $sanitizer->clean($introHtml) : null"
        :images="$bannerImages ?? []"
        fallback-image="storage/galleries/community-moments/05.jpg"
        :primary-cta="['label' => 'Become a member', 'url' => route('site.get-involved.membership')]"
        :secondary-cta="['label' => 'Volunteer', 'url' => route('site.get-involved.volunteer')]"
    />

    @if($contentBlocks->isNotEmpty())
        <x-public.blocks :blocks="$contentBlocks" :sanitizer="$sanitizer" />
    @else
        <section class="section-editorial">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head reveal">
                    <span class="eyebrow mb-3 block">Pathways</span>
                    <h2>Choose how you take part</h2>
                    <p class="section-head-row__intro">Every pathway strengthens inclusive education across the network - pick the one that fits you best.</p>
                </div>

                <div class="who-explore reveal">
                    <a href="{{ route('site.get-involved.membership') }}" class="who-explore__item">
                        <span class="who-explore__label">Membership</span>
                        <span class="who-explore__desc">Join the ASNEN network</span>
                        <span class="who-explore__arrow" aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('site.get-involved.volunteer') }}" class="who-explore__item">
                        <span class="who-explore__label">Volunteer</span>
                        <span class="who-explore__desc">Offer your time and skills</span>
                        <span class="who-explore__arrow" aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('site.get-involved.partner') }}" class="who-explore__item">
                        <span class="who-explore__label">Partner</span>
                        <span class="who-explore__desc">Explore collaboration</span>
                        <span class="who-explore__arrow" aria-hidden="true">→</span>
                    </a>
                    <a href="{{ route('site.get-involved.donate') }}" class="who-explore__item">
                        <span class="who-explore__label">Donate</span>
                        <span class="who-explore__desc">Support a programme</span>
                        <span class="who-explore__arrow" aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </section>
    @endif
@endsection
