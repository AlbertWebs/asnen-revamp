@props(['html' => ''])

<div {{ $attributes->merge(['class' => 'prose prose-lg prose-forest max-w-none prose-headings:font-display prose-headings:text-forest prose-a:text-teal hover:prose-a:text-forest']) }}>
    {!! $html !!}
</div>
