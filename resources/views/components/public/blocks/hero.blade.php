@php
    $c = $content;
    $image = ! empty($c['image_id'])
        ? \App\Models\MediaAsset::query()->find($c['image_id'])
        : null;
@endphp
<x-public.hero
    :headline="$c['headline'] ?? ''"
    :supporting-text="$c['supporting_text'] ?? null"
    :primary-cta="$c['primary_cta'] ?? null"
    :secondary-cta="$c['secondary_cta'] ?? null"
    :image="$image"
    :image-alt="$c['image_alt'] ?? null"
/>
