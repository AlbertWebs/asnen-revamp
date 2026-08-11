@extends('layouts.public')

@section('title', ($page->title ?? 'Volunteer').' | '.$siteName)
@section('meta_description', $page->excerpt ?? 'Offer your time and skills to advance ASNEN\'s mission.')

@section('content')
    @php
        $page?->loadMissing('blocks');
        $introHtml = $page?->blocks->firstWhere('type', 'rich_text')?->content['body'] ?? null;
        $pathways = [
            ['label' => 'Membership', 'url' => route('site.get-involved.membership'), 'desc' => 'Join the ASNEN network'],
            ['label' => 'Partner', 'url' => route('site.get-involved.partner'), 'desc' => 'Explore collaboration'],
            ['label' => 'Donate', 'url' => route('site.get-involved.donate'), 'desc' => 'Support a programme'],
        ];
    @endphp

    <x-public.media-hero
        :title="$page->title ?? 'Volunteer'"
        title-max="12ch"
        heading-id="volunteer-hero-heading"
        current-label="Volunteer"
        eyebrow="Walk with us"
        :excerpt="$page?->excerpt"
        :body-html="$introHtml ? $sanitizer->clean($introHtml) : null"
        :images="$bannerImages ?? []"
        fallback-image="storage/galleries/community-moments/07.jpg"
        :primary-cta="['label' => 'Apply to volunteer', 'url' => '#volunteer-application']"
        :secondary-cta="['label' => 'All pathways', 'url' => route('site.get-involved.index')]"
    />

    <section id="volunteer-application" class="section-editorial volunteer-section">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="volunteer-layout reveal">
                <div class="volunteer-copy">
                    <span class="eyebrow mb-3 block">Why volunteer</span>
                    <h2 class="volunteer-copy__title">Share your skills with the network</h2>
                    <p class="volunteer-copy__intro">ASNEN welcomes volunteers who can support programmes, events, outreach, and communications. Tell us what you can offer and when you are available - we will follow up when there is a good match.</p>

                    <ul class="volunteer-points" aria-label="How volunteering works">
                        <li>Share your skills, experience, and interests</li>
                        <li>Tell us your availability and preferred ways to help</li>
                        <li>Our team reviews applications and follows up by email</li>
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
                        <h2 class="volunteer-panel__title">Volunteer application</h2>
                        <p class="volunteer-panel__hint">Required fields are marked. We only use your details to respond about volunteering.</p>
                    </div>

                    <x-public.form
                        :action="route('site.get-involved.volunteer.store')"
                        submit-label="Submit volunteer application"
                        class="site-form"
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
                                <label for="phone" class="site-form__label">Phone</label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    autocomplete="tel"
                                    class="site-form__input @error('phone') site-form__input--error @enderror"
                                    @if($errors->has('phone')) aria-invalid="true" aria-describedby="phone-error" @endif
                                >
                                @error('phone')
                                    <p id="phone-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="site-form__field site-form__field--full">
                                <label for="skills" class="site-form__label">Skills and experience</label>
                                <p id="skills-help" class="site-form__help">Mention training, languages, programme areas, or practical skills you can offer.</p>
                                <textarea
                                    id="skills"
                                    name="skills"
                                    rows="5"
                                    required
                                    class="site-form__input site-form__textarea @error('skills') site-form__input--error @enderror"
                                    aria-describedby="skills-help{{ $errors->has('skills') ? ' skills-error' : '' }}"
                                    @if($errors->has('skills')) aria-invalid="true" @endif
                                >{{ old('skills') }}</textarea>
                                @error('skills')
                                    <p id="skills-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="site-form__field site-form__field--full">
                                <label for="availability" class="site-form__label">Availability</label>
                                <p id="availability-help" class="site-form__help">For example: evenings, weekends, a few hours each week, or event-based support.</p>
                                <input
                                    type="text"
                                    id="availability"
                                    name="availability"
                                    value="{{ old('availability') }}"
                                    required
                                    class="site-form__input @error('availability') site-form__input--error @enderror"
                                    aria-describedby="availability-help{{ $errors->has('availability') ? ' availability-error' : '' }}"
                                    @if($errors->has('availability')) aria-invalid="true" @endif
                                    placeholder="Weekends and weekday evenings"
                                >
                                @error('availability')
                                    <p id="availability-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-public.form>
                </div>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Prefer another way to help?"
        text="Membership, partnership, and programme support are other ways to strengthen inclusive education with ASNEN."
        :primary-cta="['label' => 'Explore all pathways', 'url' => route('site.get-involved.index')]"
        :secondary-cta="['label' => 'Contact us', 'url' => route('site.contact')]"
    />
@endsection
