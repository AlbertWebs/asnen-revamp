@extends('layouts.public')

@section('title', ($page?->seoMeta?->title ?? $page?->title ?? 'Contact').' | '.$siteName)
@section('meta_description', $page?->seoMeta?->description ?? $page?->excerpt ?? 'Reach the Africa Special Needs Education Network.')

@section('content')
    @php
        $pathways = [
            ['label' => 'Get involved', 'url' => route('site.get-involved.index'), 'desc' => 'Membership, volunteering, and more'],
            ['label' => 'Partner with us', 'url' => route('site.get-involved.partner'), 'desc' => 'Explore collaboration'],
            ['label' => 'FAQs', 'url' => route('site.faqs'), 'desc' => 'Quick answers before you write'],
        ];

        $channels = collect([
            $contactEmail ? [
                'label' => 'Email',
                'value' => $contactEmail,
                'href' => 'mailto:'.$contactEmail,
                'hint' => 'Best for programme and partnership questions',
            ] : null,
            $contactPhone ? [
                'label' => 'Phone',
                'value' => $contactPhone,
                'href' => 'tel:'.preg_replace('/\s+/', '', $contactPhone),
                'hint' => 'Primary line',
            ] : null,
            $contactPhoneSecondary ? [
                'label' => 'Phone',
                'value' => $contactPhoneSecondary,
                'href' => 'tel:'.preg_replace('/\s+/', '', $contactPhoneSecondary),
                'hint' => 'Secondary line',
            ] : null,
            $contactCity ? [
                'label' => 'Based in',
                'value' => $contactCity,
                'href' => null,
                'hint' => 'Serving communities across Kenya and beyond',
            ] : null,
        ])->filter()->values();
    @endphp

    <x-public.about-hero
        breadcrumb="Home"
        :breadcrumb-url="route('site.home')"
        current-label="Contact"
        :title="$page?->title ?? 'Contact'"
        title-max="10ch"
        tagline="We read every message."
        :excerpt="$page?->excerpt ?? 'Reach ASNEN about programmes, partnerships, events, membership, or how to walk with the network.'"
        :primary-cta="['label' => 'Write a message', 'url' => '#contact-form']"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
    />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="who-identity reveal">
                <div class="who-identity__copy">
                    <span class="eyebrow mb-3 block">Before you write</span>
                    <h2>How we can help</h2>
                    <div class="who-identity__body">
                        <p class="text-lg leading-relaxed text-charcoal-500">
                            We welcome questions about inclusive education programmes, events, membership, volunteering, partnerships, and media. Tell us what you need and the best way to reach you.
                        </p>
                    </div>
                </div>
                <aside class="who-identity__aside">
                    <p class="who-identity__aside-label">What to expect</p>
                    <p class="who-identity__aside-quote">We aim to reply within a few working days.</p>
                    <ul class="who-identity__aside-list">
                        <li>Programme and event questions</li>
                        <li>Partnership and membership inquiries</li>
                        <li>Press and speaking requests</li>
                    </ul>
             