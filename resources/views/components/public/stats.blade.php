@props(['metrics', 'footnote' => null])

@if($metrics->isNotEmpty())
<section class="stat-sentence" aria-label="Impact highlights">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <p class="reveal">
            @foreach($metrics as $i => $metric)
                <span class="num">{{ $metric->public_label ?: $metric->value }}{{ $metric->unit ? ' '.$metric->unit : '' }}</span>
                {{ $metric->label }}{{ $i < $metrics->count() - 1 ? '. ' : '.' }}
            @endforeach
        </p>
        @if($footnote)
            <p class="reveal mt-4 max-w-3xl font-sans text-sm not-italic text-brand-100">{{ $footnote }}</p>
        @endif
    </div>
</section>
@endif
