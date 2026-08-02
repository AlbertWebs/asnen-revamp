@extends('layouts.public')

@section('title', ($page?->seoMeta?->title ?? $program->title).' | '.$siteName)
@section('meta_description', $page?->seoMeta?->description ?? $program->summary)

@section('content')
    @php
        $focusBySlug = [
            'inclusive-education' => [
                'eyebrow' => 'In the classroom',
                'heading' => 'How inclusive education takes shape',
                'intro' => 'ASNEN works with schools and educators so every learner is welcomed with dignity and high expectations.',
                'items' => [
                    ['title' => 'Teacher training', 'body' => 'Practical skills for welcoming neurodiversity, learning differences, and diverse support needs.'],
                    ['title' => 'Classroom practice', 'body' => 'Approaches that keep children in the learning community instead of at the margins.'],
                    ['title' => 'School collaboration', 'body' => 'Partnership with school leaders so inclusion becomes culture, not a one-off event.'],
                    ['title' => 'Policy dialogue', 'body' => 'Connecting classroom reality with policymakers who shape inclusive education systems.'],
                    ['title' => 'Family partnership', 'body' => 'Working with caregivers so home and school pull in the same direction.'],
                    ['title' => 'High expectations', 'body' => 'Holding the belief that every child can learn, belong, and contribute.'],
                ],
            ],
            'caregiver-training' => [
                'eyebrow' => 'With families',
                'heading' => 'How caregiver training takes shape',
                'intro' => 'ASNEN strengthens the people closest to the child with skills, peer support, and lasting community.',
                'items' => [
                    ['title' => 'Practical skills', 'body' => 'Evidence-informed strategies caregivers can use at home and in daily routines.'],
                    ['title' => 'Peer support', 'body' => 'Spaces where parents and guardians learn from one another without isolation.'],
                    ['title' => 'Ongoing frameworks', 'body' => 'Support that continues beyond a single workshop.'],
                    ['title' => 'Compassion first', 'body' => 'Training rooted in reciprocity, dignity, and Ubuntu.'],
                ],
            ],
            'early-identification-intervention' => [
                'eyebrow' => 'Earlier support',
                'heading' => 'How early identification takes shape',
                'intro' => 'ASNEN helps communities shorten the path from concern to assessment, referral, and care.',
                'items' => [
                    ['title' => 'Community pathways', 'body' => 'Clearer routes from noticing a need to getting the right support.'],
                    ['title' => 'School readiness', 'body' => 'Helping educators recognise and respond early.'],
                    ['title' => 'Health partnerships', 'body' => 'Linking education and health so children are not left waiting.'],
                    ['title' => 'Reduced delay', 'body' => 'Acting when support can make the greatest difference.'],
                ],
            ],
            'disability-awareness-advocacy' => [
                'eyebrow' => 'Rights & voice',
                'heading' => 'How advocacy takes shape',
                'intro' => 'ASNEN challenges stigma and advances rights-based advocacy with lived experience at the centre.',
                'items' => [
                    ['title' => 'Awareness workshops', 'body' => 'Shifting how communities understand disability and belonging.'],
                    ['title' => 'Campaigns', 'body' => 'Public messages that honour dignity and challenge exclusion.'],
                    ['title' => 'Coalition building', 'body' => 'Working with allies so advocacy is stronger together.'],
                    ['title' => 'Nothing about us without us', 'body' => 'Centering the voices of persons with disabilities in every agenda.'],
                ],
            ],
            'social-inclusion' => [
                'eyebrow' => 'Belonging',
                'heading' => 'How social inclusion takes shape',
                'intro' => 'ASNEN fosters belonging beyond the classroom through community, friendship, and shared humanity.',
                'items' => [
                    ['title' => 'Community activities', 'body' => 'Spaces where children and young adults participate fully.'],
                    ['title' => 'Peer connection', 'body' => 'Friendships and mutual understanding rooted in Ubuntu.'],
                    ['title' => 'Confidence building', 'body' => 'Opportunities that grow voice, agency, and joy.'],
                    ['title' => 'Celebrate diversity', 'body' => 'Programmes that treat difference as part of community life.'],
                ],
            ],
            'research-policy-partnerships' => [
                'eyebrow' => 'Evidence & systems',
                'heading' => 'How research and partnerships take shape',
                'intro' => 'ASNEN connects African evidence, policy dialogue, and partners to advance inclusive systems.',
                'items' => [
                    ['title' => 'Context-relevant knowledge', 'body' => 'Evidence that reflects African classrooms and communities.'],
                    ['title' => 'Policy dialogue', 'body' => 'Bringing practice into conversations that shape systems.'],
                    ['title' => 'Strategic partnerships', 'body' => 'Collaborations that last beyond a single project.'],
                    ['title' => 'Scale with integrity', 'body' => 'Growing inclusion without losing local wisdom.'],
                ],
            ],
            'community-outreach-medical-camps' => [
                'eyebrow' => 'In the field',
                'heading' => 'How outreach takes shape',
                'intro' => 'ASNEN brings assessment, registration, and specialist support to communities that are often left waiting.',
                'items' => [
                    ['title' => 'Medical camps', 'body' => 'Coordinated days for assessment, registration, and referral.'],
                    ['title' => 'Underserved communities', 'body' => 'Meeting families where they are, not only where services sit.'],
                    ['title' => 'Stigma reduction', 'body' => 'Local awareness that opens doors to belonging.'],
                    ['title' => 'Partner networks', 'body' => 'Health, education, and community actors working as one.'],
                ],
            ],
        ];

        $focus = $focusBySlug[$program->slug] ?? [
            'eyebrow' => 'Programme focus',
            'heading' => 'How this work takes shape',
            'intro' => $program->summary,
            'items' => [
                ['title' => 'Knowledge', 'body' => 'Practical information communities can use.'],
                ['title' => 'Capacity', 'body' => 'Skills for educators, caregivers, and partners.'],
                ['title' => 'Collaboration', 'body' => 'Working across schools, health, and community.'],
                ['title' => 'Dignity', 'body' => 'Every action rooted in belonging and respect.'],
            ],
        ];

        $asideBySlug = [
            'inclusive-education' => [
                'label' => 'Our commitment',
                'quote' => 'No child left behind.',
                'points' => [
                    'Respect for neurodiversity',
                    'High expectations for every learner',
                    'Schools as communities of belonging',
                ],
            ],
        ];

        $aside = $asideBySlug[$program->slug] ?? [
            'label' => 'Our commitment',
            'quote' => 'Inclusion for all, in all.',
            'points' => [
                'Dignity-centred practice',
                'Homegrown African models',
                'Partnership with communities',
            ],
        ];
    @endphp

    <x-public.about-hero
        breadcrumb="What we do"
        :breadcrumb-url="route('site.programs.index')"
        :current-label="$program->title"
        :title="$program->title"
        title-max="16ch"
        tagline="A programme of ASNEN."
        :excerpt="$program->summary"
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'All programmes', 'url' => route('site.programs.index')]"
        :show-visual="true"
    />

    <x-public.program-subnav :current="$program->slug" :programs="$allPrograms" />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="program-detail reveal">
                <div class="program-detail__copy">
                    <span class="eyebrow mb-3 block">About this programme</span>
                    <h2>What we deliver</h2>
                    @if($program->body)
                        <div class="program-detail__body">
                            <x-public.prose :html="$sanitizer->clean($program->body)" />
                        </div>
                    @endif

                    <div class="program-detail__media">
                        <x-public.media-frame
                            :asset="$program->featuredImage"
                            :alt="$program->featuredImage?->alt ?? $program->title"
                            ratio="16/9"
                            rounded="rounded-2xl"
                            label="Programme photo"
                        />
                    </div>
                </div>

                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">{{ $aside['label'] }}</p>
                    <p class="who-identity__aside-quote">{{ $aside['quote'] }}</p>
                    <ul class="who-identity__aside-list">
                        @foreach($aside['points'] as $point)
                            <li>{{ $point }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('site.get-involved.partner') }}" class="who-identity__aside-link">
                        Partner on this programme
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
                    <span class="eyebrow mb-3 block">{{ $focus['eyebrow'] }}</span>
                    <h2>{{ $focus['heading'] }}</h2>
                    <p class="section-head-row__intro">{{ $focus['intro'] }}</p>
                </div>
                <a href="{{ route('site.impact.overview') }}" class="btn-secondary section-head-row__cta">See our impact</a>
            </div>

            <ol class="who-pillars reveal">
                @foreach($focus['items'] as $index => $item)
                    <li class="who-pillar">
                        <span class="who-pillar__num" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="who-pillar__title">{{ $item['title'] }}</h3>
                        <p class="who-pillar__body">{{ $item['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    @if($relatedStories->isNotEmpty())
        <section class="section-editorial">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">From the field</span>
                        <h2>Stories linked to this work</h2>
                        <p class="section-head-row__intro">Evidence and learning from programmes connected to {{ $program->title }}.</p>
                    </div>
                    <a href="{{ route('site.impact.stories') }}" class="btn-secondary section-head-row__cta">All stories</a>
                </div>

                <div class="impact-story-grid reveal">
                    @foreach($relatedStories as $story)
                        @php
                            $href = $story->slug === 'komolion-2023-disability-assessment-medical-camp'
                                ? route('site.impact.komolion')
                                : route('site.impact.stories.show', $story->slug);
                        @endphp
                        <article class="impact-story-card">
                            <a href="{{ $href }}" class="impact-story-card__media">
                                <x-public.media-frame
                                    :asset="$story->featuredImage"
                                    :alt="$story->featuredImage?->alt ?? $story->title"
                                    ratio="16/9"
                                    rounded="rounded-none"
                                    label="Story photo"
                                />
                            </a>
                            <div class="impact-story-card__body">
                                <h3 class="impact-story-card__title">
                                    <a href="{{ $href }}">{{ $story->title }}</a>
                                </h3>
                                @if($story->summary)
                                    <p class="impact-story-card__summary">{{ \Illuminate\Support\Str::limit($story->summary, 140) }}</p>
                                @endif
                                <a href="{{ $href }}" class="impact-story-card__link">
                                    Read story
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($otherPrograms->isNotEmpty())
        <section class="section-editorial {{ $relatedStories->isNotEmpty() ? 'bg-sand' : '' }}">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head reveal">
                    <span class="eyebrow mb-3 block">Keep exploring</span>
                    <h2>Other ASNEN programmes</h2>
                    <p class="section-head-row__intro">Inclusive education sits alongside caregiver support, outreach, advocacy, and partnerships.</p>
                </div>

                <div class="who-explore reveal">
                    @foreach($otherPrograms as $other)
                        <a href="{{ route('site.programs.show', $other->slug) }}" class="who-explore__item">
                            <span class="who-explore__label">{{ $other->title }}</span>
                            <span class="who-explore__desc">{{ \Illuminate\Support\Str::limit($other->summary, 90) }}</span>
                            <span class="who-explore__arrow" aria-hidden="true">→</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-public.cta-band
        heading="Walk with this programme"
        text="Membership, volunteering, partnership, and giving all strengthen inclusive education across Africa."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
