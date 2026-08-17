@extends('layouts.public')

@section('title', ($page->seoMeta?->title ?? $page->title).' | '.$siteName)
@section('meta_description', $page->seoMeta?->description ?? $page->excerpt)

@section('content')
    <x-public.media-hero
        parent-label="About"
        :parent-url="route('site.about.who-we-are')"
        current-label="Collaborators"
        eyebrow="Our network"
        title="Collaborators"
        title-max="14ch"
        tagline="Stronger together."
        :excerpt="$page->excerpt"
        :primary-cta="['label' => 'Partner with us', 'url' => route('site.get-involved.partner')]"
        :secondary-cta="['label' => 'Become a member', 'url' => route('site.get-involved.membership')]"
        :images="$bannerImages ?? []"
    />

    <x-public.about-subnav current="partners" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">Collaboration</span>
                    <h2>Organisations walking with ASNEN</h2>
                    @if(!empty($introHtml))
                        <div class="who-identity__body">
                            <x-public.prose :html="$sanitizer->clean($introHtml)" />
                        </div>
                    @endif
                </div>

                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">Who collaborates with us</p>
                    <p class="who-identity__aside-quote">Schools, NGOs, health institutions, and community organisations.</p>
                    <ul class="who-identity__aside-list">
                        <li>Shared commitment to dignity</li>
                        <li>Verified logos and profiles</li>
                        <li>Programmes strengthened together</li>
                    </ul>
                    <a href="{{ route('site.get-involved.partner') }}" class="who-identity__aside-link">
                        Start a partnership inquiry
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
                    <span class="eyebrow mb-3 block">Collaborator directory</span>
                    <h2>Collaborators</h2>
                    <p class="section-head-row__intro">Logos and names appear here after verification by ASNEN administrators.</p>
                </div>
                <a href="{{ route('site.get-involved.partner') }}" class="btn-secondary section-head-row__cta">Partner with us</a>
            </div>

            <aside class="program-launch reveal mt-8" aria-labelledby="partners-beyond-zero-heading">
                <span class="eyebrow mb-3 block">With Beyond Zero</span>
                <h3 id="partners-beyond-zero-heading">Launch of the caregivers manual</h3>
                <p>Together with Beyond Zero, ASNEN launched the caregivers manual. It is now available in Toolkits and Guides as the Caregiver Support Toolkit.</p>
                <a href="{{ route('site.resources.publications.show', 'caregiver-support-toolkit') }}" class="btn-secondary mt-4 inline-flex">
                    Open the caregivers manual
                </a>
            </aside>

            <div class="about-partners reveal mt-8">
                <x-public.partner-logos :partners="$partners" layout="grid" />
            </div>
        </div>
    </section>

    <x-public.about-explore current="partners" />

    <x-public.cta-band
        heading="Build with ASNEN"
        text="Whether you represent a school, NGO, health partner, or community organisation, we welcome collaboration rooted in dignity."
        :primary-cta="['label' => 'Partner with us', 'url' => route('site.get-involved.partner')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
