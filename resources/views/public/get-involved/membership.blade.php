@extends('layouts.public')

@section('title', ($page->title ?? 'Become a Member').' | '.$siteName)
@section('meta_description', $page->excerpt ?? 'Join ASNEN\'s network of advocates for inclusive education.')

@section('content')
    @php
        $page?->loadMissing('blocks');
        $introHtml = $page?->blocks->firstWhere('type', 'rich_text')?->content['body'] ?? null;
        $pathways = [
            ['label' => 'Volunteer', 'url' => route('site.get-involved.volunteer'), 'desc' => 'Offer your time and skills'],
            ['label' => 'Partner', 'url' => route('site.get-involved.partner'), 'desc' => 'Explore collaboration'],
            ['label' => 'Donate', 'url' => route('site.get-involved.donate'), 'desc' => 'Support a programme'],
        ];
        $selectedType = old('membership_type', 'individual');
    @endphp

    <x-public.media-hero
        :title="$page->title ?? 'Become a Member'"
        title-max="14ch"
        heading-id="membership-hero-heading"
        current-label="Membership"
        eyebrow="Belong to the network"
        :excerpt="$page?->excerpt"
        :body-html="$introHtml ? $sanitizer->clean($introHtml) : null"
        :images="$bannerImages ?? []"
        fallback-image="storage/galleries/community-moments/03.jpg"
        :primary-cta="['label' => 'Apply for membership', 'url' => '#membership-application']"
        :secondary-cta="['label' => 'Why membership matters', 'url' => '#why-membership-matters']"
    />

    @php
        $membershipCopy = config('membership_page', []);
        $why = $membershipCopy['why'] ?? [];
        $reasons = $membershipCopy['reasons'] ?? [];
    @endphp

    @if(! empty($why['paragraphs']))
        <section id="why-membership-matters" class="section-editorial" aria-labelledby="why-membership-heading">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="program-detail program-detail--deliver reveal">
                    <div class="program-detail__main">
                        <header class="program-detail__header">
                            <span class="eyebrow program-detail__eyebrow">{{ $why['eyebrow'] ?? 'Why membership matters' }}</span>
                            <h2 id="why-membership-heading">{{ $why['heading'] ?? 'Why membership matters' }}</h2>
                            <div class="program-detail__body">
                                @foreach($why['paragraphs'] as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                        </header>
                    </div>

                    <div class="program-detail__side">
                        <figure class="program-detail__figure">
                            <div class="program-detail__media">
                                <div class="overflow-hidden rounded-2xl bg-sand" style="aspect-ratio: 4/5;">
                                    <img
                                        src="{{ asset($why['image'] ?? 'storage/galleries/community-moments/01.jpg') }}"
                                        alt="{{ $why['image_alt'] ?? 'ASNEN community gathering' }}"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                </div>
                            </div>
                            <figcaption class="program-detail__caption">
                                <span class="program-detail__caption-label">{{ $why['caption_label'] ?? 'The network' }}</span>
                                <span class="program-detail__caption-text">{{ $why['caption_text'] ?? 'Never facing a system alone.' }}</span>
                            </figcaption>
                        </figure>
                        <p class="program-detail__side-cta">
                            <a href="#membership-application">
                                Apply for membership
                                <span aria-hidden="true">→</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(! empty($reasons['items']))
        <section class="section-editorial bg-sand" aria-labelledby="membership-reasons-heading">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">{{ $reasons['eyebrow'] ?? 'Five reasons to join' }}</span>
                        <h2 id="membership-reasons-heading">{{ $reasons['heading'] ?? 'Five reasons to join' }}</h2>
                        @if(! empty($reasons['intro']))
                            <p class="section-head-row__intro">{{ $reasons['intro'] }}</p>
                        @endif
                    </div>
                    <a href="#membership-application" class="btn-secondary section-head-row__cta">Apply now</a>
                </div>

                <ul class="program-deliverables reveal" aria-label="Five reasons to join ASNEN">
                    @foreach($reasons['items'] as $index => $item)
                        <li class="program-deliverable" style="--deliver-i: {{ $index }};">
                            <span class="program-deliverable__index" aria-hidden="true">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="program-deliverable__copy">
                                <h3 class="program-deliverable__title">{{ $item['title'] }}</h3>
                                <p class="program-deliverable__body">{{ $item['body'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @if($plans->isNotEmpty())
        <section class="section-editorial">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head reveal">
                    <span class="eyebrow mb-3 block">Membership options</span>
                    <h2>Choose how you belong</h2>
                    <p class="section-head-row__intro">Individual and organisational pathways keep educators, caregivers, advocates, and institutions connected to ASNEN's work.</p>
                </div>

                <div class="membership-plan-grid reveal">
                    @foreach($plans as $plan)
                        @php
                            $typeValue = $plan->slug === 'organizational' ? 'organizational' : 'individual';
                            $benefits = is_array($plan->benefits) ? $plan->benefits : [];
                        @endphp
                        <article class="membership-plan">
                            <div class="membership-plan__top">
                                <p class="membership-plan__eyebrow">{{ $plan->slug === 'organizational' ? 'Organisations' : 'People' }}</p>
                                <h3 class="membership-plan__title">{{ $plan->name }}</h3>
                                @if($plan->summary)
                                    <p class="membership-plan__summary">{{ $plan->summary }}</p>
                                @endif
                            </div>

                            @if(count($benefits))
                                <ul class="membership-plan__benefits" aria-label="{{ $plan->name }} benefits">
                                    @foreach($benefits as $benefit)
                                        <li>{{ $benefit }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if($plan->eligibility)
                                <p class="membership-plan__eligibility"><span>Eligibility:</span> {{ $plan->eligibility }}</p>
                            @endif

                            <a
                                href="#membership-application"
                                class="btn-secondary membership-plan__cta"
                                data-membership-type="{{ $typeValue }}"
                                onclick="document.getElementById('membership_type').value = this.dataset.membershipType;"
                            >
                                Apply as {{ strtolower($plan->name) }}
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="membership-application" class="section-editorial volunteer-section">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="volunteer-layout reveal">
                <div class="volunteer-copy">
                    <span class="eyebrow mb-3 block">Application</span>
                    <h2 class="volunteer-copy__title">Join the ASNEN network</h2>
                    <p class="volunteer-copy__intro">Membership is belonging to a community carrying this work together. Complete the form and our team will follow up by email.</p>

                    <ul class="volunteer-points" aria-label="What happens next">
                        <li>Choose individual or organisational membership</li>
                        <li>Share why you want to join ASNEN</li>
                        <li>We review applications and respond with next steps</li>
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
                        <h2 class="volunteer-panel__title">Membership application</h2>
                        <p class="volunteer-panel__hint">Required fields are marked. We use your details only to process your membership inquiry.</p>
                    </div>

                    <x-public.form
                        :action="route('site.get-involved.membership.store')"
                        submit-label="Submit membership application"
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
                                <label for="membership_type" class="site-form__label">Membership type</label>
                                <select
                                    id="membership_type"
                                    name="membership_type"
                                    required
                                    class="site-form__input @error('membership_type') site-form__input--error @enderror"
                                    @if($errors->has('membership_type')) aria-invalid="true" aria-describedby="membership_type-error" @endif
                                >
                                    <option value="individual" @selected($selectedType === 'individual')>Individual</option>
                                    <option value="organizational" @selected($selectedType === 'organizational')>Organisational</option>
                                </select>
                                @error('membership_type')
                                    <p id="membership_type-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="site-form__field site-form__field--full">
                                <label for="motivation" class="site-form__label">Why do you want to join?</label>
                                <p id="motivation-help" class="site-form__help">Share how you hope to contribute to inclusive education and disability inclusion.</p>
                                <textarea
                                    id="motivation"
                                    name="motivation"
                                    rows="5"
                                    required
                                    class="site-form__input site-form__textarea @error('motivation') site-form__input--error @enderror"
                                    aria-describedby="motivation-help{{ $errors->has('motivation') ? ' motivation-error' : '' }}"
                                    @if($errors->has('motivation')) aria-invalid="true" @endif
                                >{{ old('motivation') }}</textarea>
                                @error('motivation')
                                    <p id="motivation-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-public.form>
                </div>
            </div>
        </div>
    </section>

    <x-public.cta-band
        heading="Want to help in another way?"
        text="Volunteering, partnership, and programme support are other ways to walk with ASNEN."
        :primary-cta="['label' => 'Volunteer', 'url' => route('site.get-involved.volunteer')]"
        :secondary-cta="['label' => 'Explore pathways', 'url' => route('site.get-involved.index')]"
    />
@endsection
