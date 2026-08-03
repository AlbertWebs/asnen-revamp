@props([
    'story',
    'ctaUrl' => null,
    'ctaLabel' => 'Read the full story',
    'eyebrow' => null,
])

@php
    $story->loadMissing(['outcomes', 'featuredImage']);
    $href = $ctaUrl ?: route('site.impact.stories.show', $story->slug);
    $hasOutcomes = $story->outcomes->isNotEmpty();
@endphp

<article @class(['story-feature', 'story-feature--with-outcomes' => $hasOutcomes])>
    <div class="story-feature__media">
        <x-public.media-frame
            :asset="$story->featuredImage"
            :alt="$story->featuredImage?->alt ?? $story->title"
            ratio="5/4"
            rounded="rounded-sm"
            label="Story photo"
        />
    </div>

    <div class="story-feature__copy">
        @if($eyebrow)
            <span class="eyebrow">{{ $eyebrow }}</span>
        @endif
        <h3 class="story-feature__title">
            <a href="{{ $href }}">{{ $story->title }}</a>
        </h3>
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

    @if($hasOutcomes)
        <div class="story-feature__outcomes">
            <div class="story-feature__outcomes-head">
                <h4 class="story-feature__outcomes-label">Key outcomes</h4>
                <p class="story-feature__outcomes-note">Results from this outreach</p>
            </div>
            <dl class="story-feature__outcomes-list">
                @foreach($story->outcomes as $outcome)
                    <div class="story-feature__outcome">
                        <dd>{{ $outcome->value }}</dd>
                        <dt>{{ $outcome->label }}</dt>
                    </div>
                @endforeach
            </dl>
        </div>
    @endif
</article>
