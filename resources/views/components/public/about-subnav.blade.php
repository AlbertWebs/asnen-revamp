@props([
    'current' => 'who-we-are',
])

@php
    $links = [
        'who-we-are' => ['label' => 'Who we are', 'url' => route('site.about.who-we-are')],
        'leadership' => ['label' => 'Leadership & governance', 'url' => route('site.about.leadership')],
        'partners' => ['label' => 'Collaborators', 'url' => route('site.about.partners')],
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
