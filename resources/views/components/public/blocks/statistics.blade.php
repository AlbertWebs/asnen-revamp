@php
    $c = $content;
    $metricIds = $c['metric_ids'] ?? [];
    $metrics = \App\Models\ImpactMetric::published()
        ->where('verification_status', \App\Enums\VerificationStatus::Verified)
        ->when($metricIds, fn ($q) => $q->whereIn('id', $metricIds))
        ->get();
@endphp
<x-public.stats :metrics="$metrics" :footnote="$c['footnote'] ?? null" />
