@extends('layouts.public')

@section('title', $page->seoMeta?->title ?? $page->title.' | '.$siteName)
@section('meta_description', $page->seoMeta?->description ?? $page->excerpt)

@section('content')
    <x-public.media-hero
        :show-parent="false"
        :parent-label="$page->title"
        eyebrow="ASNEN"
        :title="$page->title"
        title-max="18ch"
        :excerpt="$page->excerpt"
        :images="$bannerImages ?? []"
    />

    @if(($page->slug ?? '') === 'accessibility' && ($easyReadEnabled ?? false))
        <x-public.easy-read
            title="Easy read - accessibility"
            :points="[
                'This website should work for everyone, including people with disabilities.',
                'Use the Accessibility button (bottom left) or press Alt+0 to change how the site looks.',
                'You can make text bigger, increase contrast, reduce movement, and more.',
                'If something is hard to use, tell us using the contact form.',
            ]"
        />
        <section class="mx-auto max-w-3xl px-4 pb-10 sm:px-6" aria-labelledby="a11y-tools-heading">
            <h2 id="a11y-tools-heading" class="font-display text-2xl font-bold text-forest">Built-in accessibility tools</h2>
            <p class="mt-3 text-charcoal/80">These tools work on every public page and in the admin area. Your choices are saved in this browser.</p>
            <ul class="mt-4 list-disc space-y-2 pl-5 text-charcoal">
                <li>Text size and spacing controls</li>
                <li>High contrast and dark modes</li>
                <li>Readable font (Atkinson Hyperlegible)</li>
                <li>Underline links, strong focus rings, and heading highlights</li>
                <li>Reduce motion, larger targets, and larger cursor</li>
                <li>Low saturation / grayscale and reduced images</li>
                <li>Skip links to content, navigation, footer, and preferences</li>
            </ul>
            <p class="mt-6">
                <button type="button" class="btn-primary" onclick="document.querySelector('[aria-controls=a11y-preferences-panel]')?.click()">
                    Open accessibility preferences
                </button>
            </p>
        </section>
    @endif

    <x-public.blocks :blocks="$page->blocks" :sanitizer="$sanitizer" />

    @if(($page->slug ?? '') === 'about-partners' && isset($partners))
        <x-public.section heading="Our Collaborators">
            <x-public.partner-logos :partners="$partners" />
        </x-public.section>
    @endif

    @if(($page->slug ?? '') === 'faqs' && isset($faqs))
        <x-public.section heading="Questions & Answers">
            <div class="space-y-4 max-w-3xl">
                @foreach($faqs as $faq)
                    <details class="rounded-lg border border-sand bg-ivory p-4 group">
                        <summary class="cursor-pointer font-semibold text-forest list-none flex justify-between items-center">
                            {{ $faq->question }}
                            <span class="text-teal group-open:rotate-45 transition">+</span>
                        </summary>
                        <div class="mt-3 text-charcoal/80">
                            <x-public.prose :html="$sanitizer->clean($faq->answer)" />
                        </div>
                    </details>
                @endforeach
            </div>
        </x-public.section>
    @endif
@endsection
