@php $c = $content; @endphp
<x-public.ubuntu-values
    :eyebrow="$c['eyebrow'] ?? 'Our values'"
    :heading="$c['heading'] ?? 'Written as behaviours, not aspirations - so members and partners can hold us to them.'"
    :values="$c['values'] ?? null"
/>
