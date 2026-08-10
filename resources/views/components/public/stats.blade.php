@props(['metrics', 'heading' => null, 'footnote' => null])

@if($metrics->isNotEmpty())
<section class="impact-stats" aria-label="Impact highlights">
    <div class="impact-stats__inner reveal">
        @if($heading)
            <p class="impact-stats__eyebrow">{{ $heading }}</p>
        @endif

        <div
            class="impact-stats__grid"
            style="--stat-cols: {{ min(5, max(1, $metrics->count())) }}"
            x-data="impactCounters()"
            x-init="observe($el)"
        >
            @foreach($metrics as $metric)
                @php
                    $target = (float) ($metric->numeric_value ?? 0);
                    $suffix = '';
                    $prefix = '';
                    $label = $metric->label;
                    $display = $metric->public_label ?: $metric->value;

                    // Prefer counting the numeric value; keep a simple suffix like + if present in public_label.
                    if (is_string($display) && str_ends_with($display, '+') && $target > 0) {
                        $suffix = '+';
                    }
                    if ($metric->unit && ! str_contains(strtolower($label), strtolower($metric->unit))) {
                        // unit shown as part of label already in most cases
                    }
                @endphp
                <div class="impact-stats__item">
                    <p
                        class="impact-stats__value"
                        data-count-target="{{ $target > 0 ? $target : '' }}"
                        data-count-suffix="{{ $suffix }}"
                        data-count-prefix="{{ $prefix }}"
                        aria-label="{{ $display }}"
                    >
                        <span class="impact-stats__number" data-count-display>{{ $target > 0 ? '0' : $display }}{{ $suffix }}</span>
                    </p>
                    <p class="impact-stats__label">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        @if($footnote)
            <p class="impact-stats__footnote">{{ $footnote }}</p>
        @endif
    </div>
</section>
@endif
