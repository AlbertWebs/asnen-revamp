@props([
    'story',
    'ctaUrl' => null,
    'ctaLabel' => 'Read the full story',
    'eyebrow' => 'Featured impact story',
])

@php
    $story->loadMissing(['outcomes', 'featuredImage']);
    $href = $ctaUrl ?: route('site.impact.stories.show', $story->slug);
@endphp

<article class="story-feature">
    <div class="story-feature__media">
        <x-public.media-frame
            :asset="$story->featuredImage"
            :alt="$story->featuredImage?->alt ?? $story->title"
            ratio="4/3"
            rounded="rounded-xl"
            label="Story photo"
        />
    </div>

    <div class="story-feature__copy">
        <span class="eyebrow">{{ $eyebrow }}</span>
        <h3 class="story-feature__title">{{ $story->title }}</h3>
        @if($story->summary)
            <p class="story-feature__summary">{{ $story->summary }}</p>
        @endif
        @if($story->location || $story->story_date)
            <p class="story-feature__meta">
                {{ collect([
                    $story->location,
                    $story->story_date?->format('F j, Y'),
                ])->filter()->implode(' · ') }}
            </p>
        @endif
        <a href="{{ $href }}" class="btn-primary story-feature__cta">{{ $ctaLabel }}</a>
    </div>

    @if($story->outcomes->isNotEmpty())
        <div class="story-feature__outcomes">
            <h4 class="story-feature__outcomes-label">Key outcomes</h4>
            <dl class="story-feature__outcomes-list">
                @foreach($story->outcomes as $outcome)
                    <div class="story-feature__outcome">
                        <dt>{{ $outcome->label }}</dt>
                        <dd>{{ $outcome->value }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    @endif
</article>
