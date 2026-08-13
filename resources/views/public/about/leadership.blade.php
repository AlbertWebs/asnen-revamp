@extends('layouts.public')

@section('title', ($page->seoMeta?->title ?? $page->title).' | '.$siteName)
@section('meta_description', $page->seoMeta?->description ?? $page->excerpt)

@section('content')
    @php
        $leaders = $teamMembers->take(2);
        $team = $teamMembers->slice(2)->values();
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
        current-label="Leadership & governance"
        eyebrow="Our people"
        title="Leadership & governance"
        title-max="16ch"
        tagline="People and accountability behind the work."
        :excerpt="$page->excerpt"
        :primary-cta="['label' => 'Who we are', 'url' => route('site.about.who-we-are')]"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :images="$bannerImages ?? []"
    />

    <x-public.about-subnav current="leadership" />

    @if(!empty($introHtml))
        <section class="section-editorial about-lead-intro">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="about-lead-intro__body reveal">
                    <x-public.prose :html="$sanitizer->clean($introHtml)" />
                </div>
            </div>
        </section>
    @endif

    @if($teamMembers->isEmpty())
        <section class="section-editorial">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <x-public.empty-state message="Team profiles will appear here soon." />
            </div>
        </section>
    @else
        @if($leaders->isNotEmpty())
            <section class="section-editorial leadership-featured">
                <div class="mx-auto max-w-editorial px-6 lg:px-7">
                    <div class="section-head reveal">
                        <span class="eyebrow mb-3 block">Leadership</span>
                        <h2>Guiding the network</h2>
                        <p class="section-head-row__intro">Founding and executive leadership stewarding ASNEN's mission of inclusion for all, in all.</p>
                    </div>

                    <div class="leadership-feature-grid reveal">
                        @foreach($leaders as $member)
                            <article class="leadership-feature">
                                <div class="leadership-feature__photo">
                                    <x-public.media-frame
                                        :asset="$member->photo"
                                        :alt="$member->photo?->alt ?? $member->name"
                                        ratio="1/1"
                                        rounded="rounded-2xl"
                                        label="Portrait"
                                    />
                                </div>
                                <div class="leadership-feature__copy">
                                    @if($member->title_role)
                                        <p class="leadership-feature__role">{{ $member->title_role }}</p>
                                    @endif
                                    <h3 class="leadership-feature__name">{{ $member->name }}</h3>
                                    @if($member->bio)
                                        <p class="leadership-feature__bio">{{ $member->bio }}</p>
                                    @endif
                                    @if($member->email || $member->linkedin_url)
                                        <div class="leadership-feature__links">
                                            @if($member->email)
                                                <a href="mailto:{{ $member->email }}" class="leadership-feature__link">Email</a>
                                            @endif
                                            @if($member->linkedin_url)
                                                <a href="{{ $member->linkedin_url }}" class="leadership-feature__link" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if($team->isNotEmpty())
        <section class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">The team</span>
                        <h2>Working alongside communities</h2>
                        <p class="section-head-row__intro">Programme, communications, partnerships, and creative roles that keep ASNEN connected to families, educators, and partners.</p>
                    </div>
                </div>

                <div class="team-directory reveal">
                    @foreach($team as $member)
                        <article class="team-person">
                            <div class="team-person__photo">
                                <x-public.media-frame
                                    :asset="$member->photo"
                                    :alt="$member->photo?->alt ?? $member->name"
                                    ratio="1/1"
                                    rounded="rounded-full"
                                    label="Portrait"
                                />
                            </div>
                            <div class="team-person__copy">
                                <h3 class="team-person__name">{{ $member->name }}</h3>
                                @if($member->title_role)
                                    <p class="team-person__role">{{ $member->title_role }}</p>
                                @endif
                                @if($member->bio)
                                    <p class="team-person__bio">{{ $member->bio }}</p>
                                @endif
                                @if($member->email || $member->linkedin_url)
                                    <div class="team-person__links">
                                        @if($member->email)
                                            <a href="mailto:{{ $member->email }}" class="team-person__link">Email</a>
                                        @endif
                                        @if($member->linkedin_url)
                                            <a href="{{ $member->linkedin_url }}" class="team-person__link" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    @endif

    <section id="governance" class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">How we are accountable</span>
                    <h2>Structures that protect trust</h2>
                    @if(!empty($governanceIntroHtml))
                        <div class="who-identity__body">
                            <x-public.prose :html="$sanitizer->clean($governanceIntroHtml)" />
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
                    <a href="{{ route('site.safeguarding') }}" class="who-identity__aside-link">
                        Safeguarding
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

    <x-public.about-explore current="leadership" />

    <x-public.cta-band
        heading="Want to walk with this team?"
        text="Join as a member, volunteer, partner, or supporter - and help carry inclusive education forward across Africa."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact ASNEN', 'url' => route('site.contact')]"
    />
@endsection
