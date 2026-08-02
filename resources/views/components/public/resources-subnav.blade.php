@props([
    'current' => 'index',
])

@php
    $links = [
        'index' => ['label' => 'Overview', 'url' => route('site.resources.index')],
        'publications' => ['label' => 'Publications', 'url' => route('site.resources.publications')],
        'toolkits' => ['label' => 'Toolkits', 'url' => route('site.resources.toolkits')],
        'webinars' => ['label' => 'Webinar library', 'url' => route('site.resources.webinars')],
        'news' => ['label' => 'News', 'url' => route('site.resources.news')],
        'gallery' => ['label' => 'Gallery', 'url' => route('site.resources.gallery.index')],
    ];
@endphp

<nav class="about-subnav" aria-label="Resources">
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
