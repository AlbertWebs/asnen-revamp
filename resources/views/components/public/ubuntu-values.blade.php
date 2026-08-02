@props([
    'eyebrow' => 'Our values',
    'heading' => 'Written as behaviours, not aspirations - so members and partners can hold us to them.',
    'values' => null,
])

@php
    $values = $values ?? [
        ['term' => 'Utu', 'gloss' => 'Dignity first', 'body' => 'We begin from the dignity of the child, not the diagnosis.'],
        ['term' => 'Belonging', 'gloss' => 'I am because we are', 'body' => 'We build circles, not services - caregiver to caregiver, family to community.'],
        ['term' => 'Harambee', 'gloss' => 'Pulling together', 'body' => 'Inclusion is shared work - families, schools, health workers, government, neighbours.'],
        ['term' => 'Knowledge', 'gloss' => 'Shared, not held', 'body' => 'We move expertise outward - into homes and classrooms, in the languages people use.'],
        ['term' => 'Lived experience', 'gloss' => 'Those who carry it, shape it', 'body' => 'Caregivers and persons with disabilities lead as facilitators and decision-makers.'],
        ['term' => 'Uwazi', 'gloss' => 'Honest accounting', 'body' => 'We report accurately and account transparently to members, partners and funders.'],
    ];
@endphp

<section class="section-editorial bg-sand">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="section-head reveal">
            <span class="eyebrow mb-3 block">{{ $eyebrow }}</span>
            <h2>{{ $heading }}</h2>
        </div>

        <div class="chain reveal relative">
            <div class="chain-line hidden lg:block" aria-hidden="true"></div>
            <ol class="relative z-10 m-0 grid list-none grid-cols-1 gap-8 p-0 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 xl:gap-4">
                @foreach($values as $i => $value)
                    <li>
                        <div
                            class="mb-4 flex h-16 w-16 items-center justify-center rounded-full border-[2.5px] bg-white"
                            style="border-color: {{ ['#0C77BC','#8CC63F','#FFF200','#4A4C70','#0C77BC','#8CC63F'][$i % 6] }}"
                        >
                            <span class="font-mono text-[0.68rem] font-medium">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <p class="font-mono text-[0.72rem] uppercase tracking-wide text-brand">{{ $value['term'] }}</p>
                        <p class="mt-1 font-display text-lg font-medium italic text-charcoal">{{ $value['gloss'] }}</p>
                        <p class="mt-2 text-sm text-charcoal-500">{{ $value['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>
