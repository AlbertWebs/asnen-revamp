@php $c = $content; @endphp
<x-public.ubuntu-values
    :eyebrow="$c['eyebrow'] ?? 'Core Values'"
    :heading="$c['heading'] ?? 'Drawn from Ubuntu'"
    :intro="$c['intro'] ?? null"
    :values="$c['values'] ?? null"
/>
