@php
    $c = $content;
    $metricIds = collect($c['metric_ids'] ?? [])->filter()->map(fn ($id) => (int) $id)->values();

    $query = \App\Models\ImpactMetric::published();

    if ($metricIds->isNotEmpty()) {
        $metrics = $query->whereIn('id', $metricIds->all())->get()
            ->sortBy(fn ($metric) => $metricIds->search($metric->id))
            ->values();
    } else {
        $metrics = $query
            ->where('verification_status', \App\Enums\VerificationStatus::Verified)
            ->latest('id')
            ->get();
    }
@endphp
<x-public.stats
    :metrics="$metrics"
    :heading="$c['heading'] ?? null"
    :footnote="$c['footnote'] ?? null"
/>
