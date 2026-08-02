@props([
    'current' => 'overview',
])

@php
    $links = [
        'overview' => ['label' => 'Overview', 'url' => route('site.impact.overview')],
        'stories' => ['label' => 'Stories', 'url' => route('site.impact.stories')],
        'komolion' => ['label' => 'Komolion', 'url' => route('site.impact.komolion')],
        'reports' => ['label' => 'Reports', 'url' => route('site.impact.reports')],
        'regions' => ['label' => 'Regions', 'url' => route('site.impact.regions')],
    ];
@endphp

<nav class="about-subnav" aria-label="Impact">
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
