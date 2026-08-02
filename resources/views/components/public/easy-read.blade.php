{{-- Optional easy-read callout - hidden by default; toggled from the right --}}
@props([
    'title' => 'Easy read summary',
    'points' => [],
])

@if(count($points))
<div
    class="easy-read-fab"
    x-data="{ open: false }"
    x-cloak
    @keydown.escape.window="if (open) { open = false; $refs.toggle?.focus() }"
>
    <div
        id="easy-read-panel"
        x-show="open"
        x-transition
        x-ref="panel"
        role="region"
        aria-labelledby="easy-read-heading"
        class="easy-read-panel mb-3 w-[min(22rem,calc(100vw-2.5rem))] rounded-sm border-2 border-brand bg-sand px-5 py-4 shadow-lg"
    >
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 id="easy-read-heading" class="font-display text-xl font-medium text-charcoal">{{ $title }}</h2>
                <p class="mt-1 font-mono text-[0.7rem] uppercase tracking-wide text-brand">Short summary in plain language</p>
            </div>
            <button
                type="button"
                class="shrink-0 rounded-sm p-2 text-charcoal hover:bg-brand/10"
                @click="open = false; $refs.toggle?.focus()"
                aria-label="Hide easy read summary"
            >
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                </svg>
            </button>
        </div>
        <ul class="mt-3 list-disc space-y-2 pl-5 text-base text-charcoal-500">
            @foreach($points as $point)
                <li>{{ $point }}</li>
            @endforeach
        </ul>
    </div>

    <button
        type="button"
        x-ref="toggle"
        class="ml-auto inline-flex items-center gap-2 rounded-full bg-teal px-4 py-3 text-sm font-bold text-white shadow-lg ring-2 ring-brand-200 hover:bg-teal-600 focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-2 focus-visible:outline-gold"
        @click="open = !open"
        :aria-expanded="open.toString()"
        aria-controls="easy-read-panel"
    >
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
        </svg>
        <span x-text="open ? 'Hide easy read' : 'Easy read'"></span>
    </button>
</div>
@endif
