@extends('layouts.public')

@section('title', ($page->seoMeta?->title ?? $page->title).' | '.$siteName)
@section('meta_description', $page->seoMeta?->description ?? $page->excerpt)

@section('content')
    @php
        $pillars = [
            [
                'title' => 'Ethical stewardship',
                'body' => 'Decisions and resources are guided by the dignity of the people ASNEN serves.',
            ],
            [
                'title' => 'Safeguarding',
                'body' => 'Privacy and protection for children and persons with disabilities shape how we work and tell stories.',
            ],
            [
                'title' => 'Financial accountability',
                'body' => 'Funds and partnerships are stewarded with clear reporting and honest accounting.',
            ],
            [
                'title' => 'Community accountability',
                'body' => 'Families, members, and partners can hold ASNEN to its commitments in practice.',
            ],
        ];
    @endphp

    <x-public.media-hero
        parent-label="About"
        :parent-url="route('site.about.who-we-are')"
        current-label="Governance"
        eyebrow="Accountability"
        title="Governance"
        title-max="12ch"
        tagline="Transparency with integrity."
        :excerpt="$page->excerpt"
        :primary-cta="['label' => 'Meet leadership', 'url' => route('site.about.leadership')]"
        :secondary-cta="['label' => 'Contact ASNEN', 'url' => route('site.contact')]"
        :images="$bannerImages ?? []"
    />

    <x-public.about-subnav current="governance" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">How we are accountable</span>
                    <h2>Structures that protect trust</h2>
                    @if(!empty($introHtml))
                        <div class="who-identity__body">
                            <x-public.prose :html="$sanitizer->clean($introHtml)" />
                        </div>
                    @endif
                </div>

                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">Publishing note</p>
                    <p class="who-identity__aside-quote">Board details appear here once verified.</p>
                    <ul class="who-identity__aside-list">
                        <li>Names and roles confirmed by administrators</li>
                        <li>Safeguarding standards applied to public content</li>
                        <li>Updates shared as verification is complete</li>
                    </ul>
                    <a href="{{ route('site.about.leadership') }}" class="who-identity__aside-link">
                        View leadership
                        <span aria-hidden="true">→</span>
                    </a>
                </aside>
            </div>
        </div>
    </section>

    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Our pillars</span>
                <h2>Accountability in practice</h2>
                <p class="section-head-row__intro">Governance at ASNEN is not paperwork alone. It is how we protect people, truth, and trust.</p>
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

    <x-public.about-explore current="governance" />

    <x-public.cta-band
        heading="Questions about ASNEN?"
        text="Reach the team about partnerships, membership, safeguarding, or how we report our work."
        :primary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
        :secondary-cta="['label' => 'Safeguarding', 'url' => route('site.safeguarding')]"
    />
@endsection
