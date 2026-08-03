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
                    <a href="{{ route('site.faqs') }}" class="who-identity__aside-link">
                        Browse FAQs first
                        <span aria-hidden="true">→</span>
                    </a>
                </aside>
            </div>
        </div>
    </section>

    @if($channels->isNotEmpty())
        <section class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head reveal">
                    <span class="eyebrow mb-3 block">Direct channels</span>
                    <h2>Reach us another way</h2>
                    <p class="section-head-row__intro">Prefer email or a call? Use the details below, or send a message through the form.</p>
                </div>

                <div class="contact-channels reveal" role="list">
                    @foreach($channels as $channel)
                        @if($channel['href'])
                            <a href="{{ $channel['href'] }}" class="contact-channel" role="listitem">
                                <span class="contact-channel__label">{{ $channel['label'] }}</span>
                                <span class="contact-channel__value">{{ $channel['value'] }}</span>
                                @if($channel['hint'])
                                    <span class="contact-channel__hint">{{ $channel['hint'] }}</span>
                                @endif
                            </a>
                        @else
                            <div class="contact-channel contact-channel--static" role="listitem">
                                <span class="contact-channel__label">{{ $channel['label'] }}</span>
                                <span class="contact-channel__value">{{ $channel['value'] }}</span>
                                @if($channel['hint'])
                                    <span class="contact-channel__hint">{{ $channel['hint'] }}</span>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="contact-form" class="section-editorial volunteer-section">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="volunteer-layout reveal">
                <div class="volunteer-copy">
                    <span class="eyebrow mb-3 block">Message</span>
                    <h2 class="volunteer-copy__title">Send us a note</h2>
                    <p class="volunteer-copy__intro">Share a short message with your name and email. We use your details only to respond to this inquiry.</p>

                    <ul class="volunteer-points" aria-label="Tips for a clear message">
                        <li>Include your organisation if you are writing on behalf of one</li>
                        <li>Name the programme, event, or topic you care about</li>
                        <li>Add a phone number if a call would help</li>
                    </ul>

                    <div class="volunteer-aside">
                        <h3 class="volunteer-aside__title">Related pathways</h3>
                        <ul class="volunteer-aside__list">
                            @foreach($pathways as $pathway)
                                <li>
                                    <a href="{{ $pathway['url'] }}" class="volunteer-aside__link">
                                        <span class="volunteer-aside__label">{{ $pathway['label'] }}</span>
                                        <span class="volunteer-aside__desc">{{ $pathway['desc'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="volunteer-panel">
                    <div class="volunteer-panel__head">
                        <h2 class="volunteer-panel__title">Contact form</h2>
                        <p class="volunteer-panel__hint">Required fields are marked. We will follow up by email.</p>
                    </div>

                    <x-public.form
                        :action="route('site.contact.store')"
                        submit-label="Send message"
                    >
                        <div class="site-form__grid">
                            <div class="site-form__field site-form__field--full">
                                <label for="name" class="site-form__label">Full name</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autocomplete="name"
                                    class="site-form__input @error('name') site-form__input--error @enderror"
                                    @if($errors->has('name')) aria-invalid="true" aria-describedby="name-error" @endif
                                >
                                @error('name')
                                    <p id="name-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="site-form__field">
                                <label for="email" class="site-form__label">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    class="site-form__input @error('email') site-form__input--error @enderror"
                                    @if($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif
                                >
                                @error('email')
                                    <p id="email-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="site-form__field">
                                <label for="phone" class="site-form__label">Phone <span class="site-form__optional">(optional)</span></label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    autocomplete="tel"
                                    class="site-form__input @error('phone') site-form__input--error @enderror"
                                    @if($errors->has('phone')) aria-invalid="true" aria-describedby="phone-error" @endif
                                >
                                @error('phone')
                                    <p id="phone-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="site-form__field site-form__field--full">
                                <label for="subject" class="site-form__label">Subject</label>
                                <p id="subject-help" class="site-form__help">A short line that names your topic, for example partnership, event, or membership.</p>
                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    value="{{ old('subject') }}"
                                    required
                                    autocomplete="off"
                                    class="site-form__input @error('subject') site-form__input--error @enderror"
                                    aria-describedby="subject-help{{ $errors->has('subject') ? ' subject-error' : '' }}"
                                    @if($errors->has('subject')) aria-invalid="true" @endif
                                >
                                @error('subject')
                                    <p id="subject-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="site-form__field site-form__field--full">
                                <label for="message" class="site-form__label">Message</label>
                                <p id="message-help" class="site-form__help">Share enough context for us to reply helpfully. Avoid sending sensitive personal details about children.</p>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="6"
                                    required
                                    class="site-form__input site-form__textarea @error('message') site-form__input--error @enderror"
                                    aria-describedby="message-help{{ $errors->has('message') ? ' message-error' : '' }}"
                                    @if($errors->has('message')) aria-invalid="true" @endif
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <p id="message-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-public.form>
                </div>
            </div>
        </div>
    </section>

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">Keep exploring</span>
                <h2>Other ways to connect</h2>
                <p class="section-head-row__intro">If you already know your path, these pages may be faster than a general message.</p>
            </div>

            <div class="who-explore reveal">
                <a href="{{ route('site.get-involved.index') }}" class="who-explore__item">
                    <span class="who-explore__label">Get involved</span>
                    <span class="who-explore__desc">Membership, volunteering, partnership, and giving</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.events.index') }}" class="who-explore__item">
                    <span class="who-explore__label">Events &amp; learning</span>
                    <span class="who-explore__desc">Upcoming gatherings and registration</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.about.who-we-are') }}" class="who-explore__item">
                    <span class="who-explore__label">Who we are</span>
                    <span class="who-explore__desc">Mission, story, and leadership</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('site.faqs') }}" class="who-explore__item">
                    <span class="who-explore__label">FAQs</span>
                    <span class="who-explore__desc">Common questions answered briefly</span>
                    <span class="who-explore__arrow" aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Ready to walk with ASNEN?"
        text="Membership, volunteering, and partnership are open pathways alongside a direct message."
        :primary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'About ASNEN', 'url' => route('site.about.who-we-are')]"
    />
@endsection
