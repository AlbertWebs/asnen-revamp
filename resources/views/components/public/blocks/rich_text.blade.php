@php $c = $content; @endphp
<x-public.section>
    <x-public.prose :html="$sanitizer->clean($c['body'] ?? '')" />
</x-public.section>
