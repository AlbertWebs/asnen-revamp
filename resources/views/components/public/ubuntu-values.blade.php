@props([
    'eyebrow' => 'Core Values',
    'heading' => 'Drawn from Ubuntu',
    'intro' => 'Our values are drawn from Ubuntu, the understanding that our humanity is bound to one another. They are written as behaviours rather than aspirations, so that members, partners and funders may hold us to them.',
    'values' => null,
])

@php
    $values = $values ?? [
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
@endphp

<section class="section-editorial bg-sand">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="section-head reveal">
            <span class="eyebrow mb-3 block">{{ $eyebrow }}</span>
            <h2>{{ $heading }}</h2>
            @if($intro)
                <p class="section-head-row__intro">{{ $intro }}</p>
            @endif
        </div>

        @php
            $accents = ['#0C77BC', '#8CC63F', '#FFF200', '#4A4C70', '#0C77BC', '#8CC63F'];
        @endphp
        <div class="chain reveal relative">
            <div class="chain-line hidden lg:block" aria-hidden="true"></div>
            <ol class="chain-list relative z-10 m-0 grid list-none grid-cols-1 gap-8 p-0 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($values as $i => $value)
                    @php $accent = $accents[$i % 6]; @endphp
                    <li class="chain-item{{ $accent === '#FFF200' ? ' chain-item--light' : '' }}" style="--chain-accent: {{ $accent }};">
                        <div class="chain-item__num" aria-hidden="true">
                            <span class="chain-item__num-ring"></span>
                            <span class="chain-item__num-label">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <p class="chain-item__term font-mono text-[0.72rem] uppercase tracking-wide text-brand">{{ $value['term'] }}</p>
                        <p class="chain-item__gloss mt-1 font-display text-lg font-medium italic text-charcoal">{{ $value['gloss'] }}</p>
                        <p class="chain-item__body mt-2 text-sm leading-relaxed text-charcoal-500">{{ $value['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>
