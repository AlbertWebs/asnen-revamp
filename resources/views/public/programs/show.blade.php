@extends('layouts.public')

@section('title', ($page?->seoMeta?->title ?? $program->title).' | '.$siteName)
@section('meta_description', $page?->seoMeta?->description ?? $program->summary)

@section('content')
    @php
        $focusBySlug = [
            'inclusive-education' => [
                'eyebrow' => 'In the classroom',
                'heading' => 'How inclusive education takes shape',
                'intro' => 'ASNEN supports inclusive education through teacher training, classroom practice, and collaboration with schools and policymakers. We champion approaches that respect neurodiversity, learning differences, and diverse support needs, ensuring no child is left behind.',
                'items' => [
                    ['title' => 'Teacher training', 'body' => 'Practical skills for welcoming neurodiversity and diverse support needs.'],
                    ['title' => 'Classroom practice', 'body' => 'Approaches that keep children in the learning community, not at the margins.'],
                    ['title' => 'School collaboration', 'body' => 'Partnership with school leaders so inclusion becomes culture, not a one-off event.'],
                    ['title' => 'Family partnership', 'body' => 'Working with caregivers so home and school pull in the same direction.'],
                ],
            ],
            'caregiver-training' => [
                'eyebrow' => 'With families',
                'heading' => 'How caregiver training takes shape',
                'intro' => 'Our caregiver training equips parents, guardians, and professional caregivers with evidence-informed strategies, community connections, and ongoing support frameworks grounded in compassion and reciprocity.',
                'items' => [
                    ['title' => 'Practical skills', 'body' => 'Evidence-informed strategies for home and daily routines.'],
                    ['title' => 'Peer support', 'body' => 'Spaces where parents and guardians learn from one another.'],
                    ['title' => 'Ongoing frameworks', 'body' => 'Support that continues beyond a single workshop.'],
                ],
            ],
            'early-identification-intervention' => [
                'eyebrow' => 'Earlier support',
                'heading' => 'How early identification takes shape',
                'intro' => 'ASNEN works with communities, schools, and health partners to improve pathways to assessment, referral, and early support, reducing delays that limit opportunity and inclusion.',
                'items' => [
                    ['title' => 'Community pathways', 'body' => 'Clearer routes from noticing a need to getting the right support.'],
                    ['title' => 'School readiness', 'body' => 'Helping educators recognize and respond early.'],
                    ['title' => 'Health partnerships', 'body' => 'Linking education and health so children are not left waiting.'],
                ],
            ],
            'disability-awareness-advocacy' => [
                'eyebrow' => 'Rights & voice',
                'heading' => 'How advocacy takes shape',
                'intro' => 'Through workshops, campaigns, and coalition building, ASNEN amplifies the principle that nothing about us without us, centering lived experience in advocacy for inclusive policy and practice.',
                'items' => [
                    ['title' => 'Awareness workshops', 'body' => 'Shifting how communities understand disability and belonging.'],
                    ['title' => 'Coalition building', 'body' => 'Working with allies so advocacy is stronger together.'],
                    ['title' => 'Lived experience', 'body' => 'Centering the voices of persons with disabilities in every agenda.'],
                ],
            ],
            'social-inclusion' => [
                'eyebrow' => 'Belonging',
                'heading' => 'How social inclusion takes shape',
                'intro' => 'Social inclusion initiatives create spaces where children and young adults with disabilities participate fully in community life, building friendships, confidence, and mutual understanding rooted in Ubuntu.',
                'items' => [
                    ['title' => 'Community activities', 'body' => 'Spaces where children and young adults participate fully.'],
                    ['title' => 'Peer connection', 'body' => 'Friendships and mutual understanding rooted in Ubuntu.'],
                    ['title' => 'Confidence building', 'body' => 'Opportunities that grow voice, agency and joy.'],
                ],
            ],
            'research-policy-partnerships' => [
                'eyebrow' => 'Evidence & systems',
                'heading' => 'How research and partnerships take shape',
                'intro' => 'ASNEN collaborates with researchers, institutions, and networks to generate context-relevant knowledge, inform policy, and build sustainable partnerships for inclusion at scale.',
                'items' => [
                    ['title' => 'Context-relevant knowledge', 'body' => 'Evidence that reflects African classrooms and communities.'],
                    ['title' => 'Policy dialogue', 'body' => 'Bringing practice into conversations that shape systems.'],
                    ['title' => 'Strategic partnerships', 'body' => 'Collaborations that last beyond a single project.'],
                ],
            ],
            'community-outreach-medical-camps' => [
                'eyebrow' => 'In the field',
                'heading' => 'How outreach takes shape',
                'intro' => 'Community outreach and medical camps, such as the Komolion initiative in Baringo County, extend pathways to registration, assessment, and surgical referral while combating stigma and raising local awareness.',
                'items' => [
                    ['title' => 'Medical camps', 'body' => 'Coordinated days for assessment, registration, and referral.'],
                    ['title' => 'Underserved communities', 'body' => 'Meeting families where they are, not only where services sit.'],
                    ['title' => 'Stigma reduction', 'body' => 'Local awareness that opens doors to belonging.'],
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
            'caregiver-training' => [
                'label' => 'Our commitment',
                'quote' => 'Families are the first circle of inclusion.',
                'points' => [
                    'Skills that travel home',
                    'Peer support without isolation',
                    'Support that lasts beyond one day',
                ],
            ],
            'early-identification-intervention' => [
                'label' => 'Our commitment',
                'quote' => 'Earlier support, stronger futures.',
                'points' => [
                    'Clearer pathways to help',
                    'Schools ready to respond',
                    'Health and education linked',
                ],
            ],
            'disability-awareness-advocacy' => [
                'label' => 'Our commitment',
                'quote' => 'Nothing about us without us.',
                'points' => [
                    'Lived experience first',
                    'Communities that listen',
                    'Advocacy stronger together',
                ],
            ],
            'social-inclusion' => [
                'label' => 'Our commitment',
                'quote' => 'Belonging is community work.',
                'points' => [
                    'Full participation in community life',
                    'Friendships rooted in Ubuntu',
                    'Voice, agency, and joy',
                ],
            ],
            'research-policy-partnerships' => [
                'label' => 'Our commitment',
                'quote' => 'Evidence that serves practice.',
                'points' => [
                    'Knowledge from African contexts',
                    'Policy shaped by the field',
                    'Partnerships that endure',
                ],
            ],
            'community-outreach-medical-camps' => [
                'label' => 'Our commitment',
                'quote' => 'We meet families where they are.',
                'points' => [
                    'Assessment close to home',
                    'Registration and referral pathways',
                    'Stigma challenged in community',
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

    <x-public.media-hero
        parent-label="What we do"
        :parent-url="route('site.programs.index')"
        :current-label="$program->title"
        eyebrow="A programme of ASNEN"
        :title="$program->title"
        title-max="16ch"
        :excerpt="$program->summary"
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'All programmes', 'url' => route('site.programs.index')]"
        :images="$bannerImages ?? []"
    />

    <x-public.program-subnav :current="$program->slug" :programs="$allPrograms" />

    <section class="section-editorial" aria-labelledby="program-deliver-heading">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="program-detail program-detail--deliver reveal">
                <div class="program-detail__main">
                    <header class="program-detail__header">
                        <span class="eyebrow program-detail__eyebrow">About this programme</span>
                        <h2 id="program-deliver-heading">What we deliver</h2>
                        @if($program->body)
                            <div class="program-detail__body">
                                <x-public.prose :html="$sanitizer->clean($program->body)" />
                            </div>
                        @endif
                    </header>

                    @if(!empty($focus['items']))
                        <ul class="program-deliverables" aria-label="How this programme delivers">
                            @foreach($focus['items'] as $index => $item)
                                <li class="program-deliverable" style="--deliver-i: {{ $index }};">
                                    <span class="program-deliverable__index" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="program-deliverable__copy">
                                        <h3 class="program-deliverable__title">{{ $item['title'] }}</h3>
                                        <p class="program-deliverable__body">{{ $item['body'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($program->slug === 'caregiver-training')
                        <aside class="program-launch" aria-labelledby="caregiver-manual-heading">
                            <span class="eyebrow mb-3 block">With Beyond Zero</span>
                            <h3 id="caregiver-manual-heading">Launch of the caregivers manual</h3>
                            <p>ASNEN launched the caregivers manual with Beyond Zero. It is a training manual and facilitator guide for families of children with disability, with practical tools for homes and community settings.</p>
                            <a href="{{ route('site.resources.publications.show', 'caregiver-support-toolkit') }}" class="btn-secondary mt-4 inline-flex">
                                Open the caregivers manual
                            </a>
                        </aside>
                    @endif
                </div>

                <div class="program-detail__side">
                    <figure class="program-detail__figure">
                        <div class="program-detail__media">
                            <x-public.media-frame
                                :asset="$program->featuredImage"
                                :alt="$program->featuredImage?->alt ?? $program->title"
                                ratio="4/5"
                                rounded="rounded-2xl"
                                label="Programme photo"
                            />
                        </div>
                        <figcaption class="program-detail__caption">
                            <span class="program-detail__caption-label">{{ $aside['label'] }}</span>
                            <span class="program-detail__caption-text">{{ $aside['quote'] }}</span>
                        </figcaption>
                    </figure>
                    <p class="program-detail__side-cta">
                        <a href="{{ route('site.get-involved.partner') }}">
                            Partner on this programme
                            <span aria-hidden="true">→</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-editorial bg-sand">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head-row reveal">
                <div class="section-head">
                    <span class="eyebrow mb-3 block">{{ $focus['eyebrow'] }}</span>
                    <h2>{{ $focus['heading'] }}</h2>
                    <p class="section-head-row__intro">A closer look at the ways {{ $program->title }} shows up in homes, classrooms, and communities.</p>
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
                            $href = $story->publicUrl();
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
