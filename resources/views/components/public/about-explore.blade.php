@props([
    'current' => null,
    'heading' => 'More about ASNEN',
    'intro' => 'Go deeper into our purpose, history, people, and partnerships.',
])

@php
    $items = [
        'who-we-are' => ['label' => 'Who we are', 'desc' => 'A pan-African coalition for belonging', 'url' => route('site.about.who-we-are')],
        'mission' => ['label' => 'Vision, mission & values', 'desc' => 'What guides ASNEN day to day', 'url' => route('site.about.mission')],
        'story' => ['label' => 'Our story', 'desc' => 'How the network grew from community need', 'url' => route('site.about.story')],
        'leadership' => ['label' => 'Leadership & governance', 'desc' => 'People, accountability, and how we stay trusted', 'url' => route('site.about.leadership')],
        'partners' => ['label' => 'Collaborators', 'desc' => 'Organisations walking with ASNEN', 'url' => route('site.about.partners')],
    ];

    if ($current) {
        unset($items[$current]);
    }

    $items = array_values($items);
@endphp

<section class="section-editorial">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="section-head reveal">
            <span class="eyebrow mb-3 block">Keep exploring</span>
            <h2>{{ $heading }}</h2>
            <p class="section-head-row__intro">{{ $intro }}</p>
        </div>

        <div class="who-explore reveal">
            @foreach($items as $item)
                <a href="{{ $item['url'] }}" class="who-explore__item">
                    <span class="who-explore__label">{{ $item['label'] }}</span>
                    <span class="who-explore__desc">{{ $item['desc'] }}</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
