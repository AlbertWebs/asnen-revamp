@extends('layouts.public')

@section('title', ($page->title ?? 'Partner With Us').' | '.$siteName)
@section('meta_description', $page->excerpt ?? 'Explore strategic collaboration with ASNEN.')

@section('content')
    @php
        $page?->loadMissing('blocks');
        $introHtml = $page?->blocks->firstWhere('type', 'rich_text')?->content['body'] ?? null;
        $pathways = [
            ['label' => 'Membership', 'url' => route('site.get-involved.membership'), 'desc' => 'Join the ASNEN network'],
            ['label' => 'Volunteer', 'url' => route('site.get-involved.volunteer'), 'desc' => 'Offer your time and skills'],
            ['label' => 'Donate', 'url' => route('site.get-involved.donate'), 'desc' => 'Support a programme'],
        ];
    @endphp

    <x-public.media-hero
        :title="$page->title ?? 'Partner With Us'"
        title-max="14ch"
        heading-id="partner-hero-heading"
        current-label="Partner"
        eyebrow="Collaborate with ASNEN"
        :excerpt="$page?->excerpt"
        :body-html="$introHtml ? $sanitizer->clean($introHtml) : null"
        :images="$bannerImages ?? []"
        fallback-image="storage/galleries/baringo-2023/02.jpg"
        :primary-cta="['label' => 'Start a partnership inquiry', 'url' => '#partner-inquiry']"
        :secondary-cta="['label' => 'See current partners', 'url' => route('site.about.partners')]"
    />

    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="section-head reveal">
                <span class="eyebrow mb-3 block">How we partner</span>
                <h2>Work with us on inclusion</h2>
                <p class="section-head-row__intro">ASNEN collaborates with schools, NGOs, health partners, and community organisations that share a commitment to dignity and belonging.</p>
            </div>

            <div class="impact-principle-grid reveal">
                <div class="impact-principle">
                    <h3>Shared purpose</h3>
                    <p>Partnerships centre African homegrown models of inclusive education and disability inclusion.</p>
                </div>
                <div class="impact-principle">
                    <h3>Practical collaboration</h3>
                    <p>Joint programmes, events, outreach, research, and resource sharing that strengthen communities.</p>
                </div>
                <div class="impact-principle">
                    <h3>Clear next steps</h3>
                    <p>Tell us who you are and what you propose - our team reviews inquiries and follows up by email.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="partner-inquiry" class="section-editorial volunteer-section">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="volunteer-layout reveal">
                <div class="volunteer-copy">
                    <span class="eyebrow mb-3 block">Inquiry</span>
                    <h2 class="volunteer-copy__title">Tell us about your organisation</h2>
                    <p class="volunteer-copy__intro">Share a short proposal outlining how you hope to collaborate. ASNEN will follow up by email.</p>

                    <ul class="volunteer-points" aria-label="What to include">
                        <li>Organisation name and primary contact</li>
                        <li>Focus areas and communities you serve</li>
                        <li>How you envision working with ASNEN</li>
                    </ul>

                    <div class="volunteer-aside">
                        <h3 class="volunteer-aside__title">Other pathways</h3>
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
                        <h2 class="volunteer-panel__title">Partnership inquiry</h2>
                        <p class="volunteer-panel__hint">Required fields are marked. We use your details only to respond about partnership opportunities.</p>
                    </div>

                    <x-public.form
                        :action="route('site.get-involved.partner.store')"
                        submit-label="Submit partnership inquiry"
                    >
                        <div class="site-form__grid">
                            <div class="site-form__field site-form__field--full">
                                <label for="organisation" class="site-form__label">Organisation name</label>
                                <input
                                    type="text"
                                    id="organisation"
                                    name="organisation"
                                    value="{{ old('organisation') }}"
                                    required
                                    autocomplete="organization"
                                    class="site-form__input @error('organisation') site-form__input--error @enderror"
                                    @if($errors->has('organisation')) aria-invalid="true" aria-describedby="organisation-error" @endif
                                >
                                @error('organisation')
                                    <p id="organisation-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="site-form__field">
                                <label for="contact_name" class="site-form__label">Contact person</label>
                                <input
                                    type="text"
                                    id="contact_name"
                                    name="contact_name"
                                    value="{{ old('contact_name') }}"
                                    required
                                    autocomplete="name"
                                    class="site-form__input @error('contact_name') site-form__input--error @enderror"
                                    @if($errors->has('contact_name')) aria-invalid="true" aria-describedby="contact_name-error" @endif
                                >
                                @error('contact_name')
                                    <p id="contact_name-error" class="site-form__error">{{ $message }}</p>
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

                            <div class="site-form__field site-form__field--full">
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
                                <label for="proposal" class="site-form__label">Partnership proposal</label>
                                <p id="proposal-help" class="site-form__help">Describe the collaboration you envision, including focus areas, geography, and timing if known.</p>
                                <textarea
                                    id="proposal"
                                    name="proposal"
                                    rows="6"
                                    required
                                    class="site-form__input site-form__textarea @error('proposal') site-form__input--error @enderror"
                                    aria-describedby="proposal-help{{ $errors->has('proposal') ? ' proposal-error' : '' }}"
                                    @if($errors->has('proposal')) aria-invalid="true" @endif
                                >{{ old('proposal') }}</textarea>
                                @error('proposal')
                                    <p id="proposal-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-public.form>
                </div>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Looking for another way to help?"
        text="Membership, volunteering, and programme support are other pathways to walk with ASNEN."
        :primary-cta="['label' => 'Become a member', 'url' => route('site.get-involved.membership')]"
        :secondary-cta="['label' => 'All pathways', 'url' => route('site.get-involved.index')]"
    />
@endsection
