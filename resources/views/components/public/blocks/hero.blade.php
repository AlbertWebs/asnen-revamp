@php
    $c = $content;
    $imageIds = collect($c['image_ids'] ?? [])
        ->filter(fn ($id) => filled($id) && $id !== 'null')
        ->map(fn ($id) => (int) $id)
        ->unique()
        ->values()
        ->all();

    if ($imageIds === [] && ! empty($c['image_id'])) {
        $imageIds = [(int) $c['image_id']];
    }

    $images = [];
    if ($imageIds !== []) {
        $byId = \App\Models\MediaAsset::query()
            ->whereIn('id', $imageIds)
            ->get()
            ->keyBy('id');

        foreach ($imageIds as $id) {
            if ($byId->has($id)) {
                $images[] = $byId->get($id);
            }
        }
    }
@endphp
<x-public.hero
    :brand="$c['brand'] ?? 'Demystifying Disability'"
    :eyebrow="$c['eyebrow'] ?? 'A HOMEGROWN AFRICAN MODEL OF INCLUSION'"
    :headline="$c['headline'] ?? ''"
    :supporting-text="$c['supporting_text'] ?? null"
    :primary-cta="$c['primary_cta'] ?? null"
    :secondary-cta="$c['secondary_cta'] ?? null"
    :images="$images"
    :image-alt="$c['image_alt'] ?? null"
/>
