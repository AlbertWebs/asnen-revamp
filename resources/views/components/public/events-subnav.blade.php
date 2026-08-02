@props([
    'current' => 'index',
])

@php
    $links = [
        'index' => ['label' => 'Overview', 'url' => route('site.events.index')],
        'upcoming' => ['label' => 'Upcoming', 'url' => route('site.events.upcoming')],
        'past' => ['label' => 'Past', 'url' => route('site.events.past')],
        'webinars' => ['label' => 'Webinars', 'url' => route('site.events.webinars')],
        'ubuntu' => ['label' => 'Ubuntu Conference', 'url' => route('site.events.ubuntu-conference')],
    ];
@endphp

<nav class="about-subnav" aria-label="Events and learning">
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
