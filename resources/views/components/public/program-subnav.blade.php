@props([
    'current' => null,
    'programs' => collect(),
])

@php
    $programs = collect($programs)->values();
@endphp

@if($programs->isNotEmpty())
    <nav class="about-subnav" aria-label="ASNEN programmes">
        <div class="mx-auto flex max-w-editorial flex-wrap gap-2 px-6 lg:px-7">
            <a
                href="{{ route('site.programs.index') }}"
                class="about-subnav__link {{ $current === null ? 'about-subnav__link--current' : '' }}"
                @if($current === null) aria-current="page" @endif
            >All programmes</a>
            @foreach($programs as $program)
                @if($program->slug === $current)
                    <span class="about-subnav__link about-subnav__link--current" aria-current="page">{{ $program->title }}</span>
                @else
                    <a href="{{ route('site.programs.show', $program->slug) }}" class="about-subnav__link">{{ $program->title }}</a>
                @endif
            @endforeach
        </div>
    </nav>
@endif
