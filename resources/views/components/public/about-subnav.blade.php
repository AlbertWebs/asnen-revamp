@props([
    'current' => 'who-we-are',
])

@php
    $links = [
        'who-we-are' => ['label' => 'Who we are', 'url' => route('site.about.who-we-are')],
        'mission' => ['label' => 'Mission & values', 'url' => route('site.about.mission')],
        'story' => ['label' => 'Our story', 'url' => route('site.about.story')],
        'leadership' => ['label' => 'Leadership', 'url' => route('site.about.leadership')],
        'governance' => ['label' => 'Governance', 'url' => route('site.about.governance')],
        'partners' => ['label' => 'Partners', 'url' => route('site.about.partners')],
    ];
@endphp

<nav class="about-subnav" aria-label="About ASNEN">
    <div class="mx-auto flex max-w-editorial flex-wrap gap-2 px-6 lg:px-7">
        @foreach($links as $key => $link)
            @if($key === $current)
                <span class="about-subnav__link about-subnav__link--current" aria-current="page">{{ $link['label'] }}</span>
            @else
                <a href="{{ $link['url'] }}" class="about-subnav__link">{{ $link['label'] }}</a>
            @endif
        @endforeach
    </div>
</nav>
