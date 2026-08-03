@php
    $c = $content;
    $story = null;
    if (!empty($c['story_id'])) {
        $story = \App\Models\ImpactStory::published()->with('outcomes')->find($c['story_id']);
    } elseif (!empty($c['story_slug'])) {
        $story = \App\Models\ImpactStory::published()->with('outcomes')->where('slug', $c['story_slug'])->first();
    }
@endphp
@if($story)
    <x-public.section eyebrow="Impact" :heading="$c['heading'] ?? 'Featured Impact Story'" tone="soft">
        <x-public.story-feature :story="$story" />
    </x-public.section>
@endif
