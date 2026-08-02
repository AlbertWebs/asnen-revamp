@extends('layouts.public')

@section('title', ($page->title ?? 'Donate').' | '.$siteName)
@section('meta_description', $page->excerpt ?? 'Support ASNEN programmes through financial contributions.')

@section('content')
    @php
        $page?->loadMissing('blocks');
        $introHtml = $page?->blocks->firstWhere('type', 'rich_text')?->content['body'] ?? null;
        $pathways = [
            ['label' => 'Membership', 'url' => route('site.get-involved.membership'), 'desc' => 'Join the ASNEN network'],
            ['label' => 'Volunteer', 'url' => route('site.get-involved.volunteer'), 'desc' => 'Offer your time and skills'],
            ['label' => 'Partner', 'url' => route('site.get-involved.partner'), 'desc' => 'Explore collaboration'],
        ];
        $selectedProgram = old('program_interest', 'general');
        $featuredPrograms = $programs->take(4);
    @endphp

    <section class="impact-hero">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="impact-hero__inner reveal">
                <x-public.breadcrumbs :items="[
                    ['label' => 'Get Involved', 'url' => route('site.get-involved.index')],
                    ['label' => 'Donate'],
                ]" />
                <span class="eyebrow mt-6 block">Support the work</span>
                <h1 class="impact-hero__title" style="max-width: 16ch;">{{ $page->title ?? 'Donate / Support a Program' }}</h1>
                @if($page?->excerpt)
                    <p class="impact-hero__excerpt">{{ $page->excerpt }}</p>
                @endif
                @if($introHtml)
                    <div class="impact-hero__body">
                        <x-public.prose :html="$sanitizer->clean($introHtml)" />
                    </div>
                @endif
                <div class="impact-hero__actions">
                    <a href="#donate-inquiry" class="btn-primary">Express interest in giving</a>
                    <a href="{{ route('site.impact.overview') }}" class="btn-secondary">See our impact</a>
                </div>
            </div>
        </div>
    </section>

    @if($campaign)
        <section class="section-editorial">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="donate-campaign reveal">
                    <div class="donate-campaign__copy">
                        <span class="eyebrow mb-3 block">Current campaign</span>
                        <h2 class="donate-campaign__title">{{ $campaign->title }}</h2>
                        @if($campaign->summary)
                            <p class="donate-campaign__summary">{{ $campaign->summary }}</p>
                        @endif
                        @if($campaign->body)
                            <div class="donate-campaign__body">
                                <x-public.prose :html="$sanitizer->clean($campaign->body)" />
                            </div>
                        @endif
                        <p class="donate-campaign__note">Prefer to talk first? Use the inquiry form and ASNEN will follow up with secure giving options.</p>
                    </div>
                    <div class="donate-campaign__aside">
                        <p class="donate-campaign__aside-label">Where support can go</p>
                        <ul class="donate-campaign__list">
                            <li>Inclusive education and classroom practice</li>
                            <li>Caregiver training and family support</li>
                            <li>Community outreach and medical camps</li>
                            <li>Learning events and webinars across the network</li>
                        </ul>
                        <a href="#donate-inquiry" class="btn-secondary donate-campaign__cta">Continue to inquiry form</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($featuredPrograms->isNotEmpty())
        <section class="section-editorial {{ $campaign ? 'bg-sand' : '' }}">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="section-head-row reveal">
                    <div class="section-head">
                        <span class="eyebrow mb-3 block">Programmes</span>
                        <h2>Direct support where it matters</h2>
                        <p class="section-head-row__intro">Choose a programme focus in the form, or select general support if you prefer ASNEN to allocate where need is greatest.</p>
                    </div>
                    <a href="{{ route('site.programs.index') }}" class="btn-secondary section-head-row__cta">Browse programmes</a>
                </div>

                <div class="donate-program-grid reveal">
                    @foreach($featuredPrograms as $program)
                        <article class="donate-program">
                            <h3 class="donate-program__title">{{ $program->title }}</h3>
                            @if($program->summary)
                                <p class="donate-program__summary">{{ $program->summary }}</p>
                            @endif
                            <a
                                href="#donate-inquiry"
                                class="donate-program__link"
                                data-program="{{ $program->slug }}"
                                onclick="document.getElementById('program_interest').value = this.dataset.program;"
                            >
                                Support this programme
                                <span aria-hidden="true">→</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="donate-inquiry" class="section-editorial volunteer-section">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="volunteer-layout reveal">
                <div class="volunteer-copy">
                    <span class="eyebrow mb-3 block">Giving inquiry</span>
                    <h2 class="volunteer-copy__title">Tell us how you want to help</h2>
                    <p class="volunteer-copy__intro">Share your details and preferred programme focus. ASNEN will follow up with secure next steps for supporting the work.</p>

                    <ul class="volunteer-points" aria-label="What happens next">
                        <li>Choose general support or a programme focus</li>
                        <li>Add an optional note about your interest</li>
                        <li>Our team responds with verified giving options</li>
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
                        <h2 class="volunteer-panel__title">Support inquiry</h2>
                        <p class="volunteer-panel__hint">Share your interest below and we will follow up with next steps.</p>
                    </div>

                    <x-public.form
                        :action="route('site.get-involved.donate.store')"
                        submit-label="Submit support inquiry"
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

                            <div class="site-form__field site-form__field--full">
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
                                <label for="program_interest" class="site-form__label">Programme to support</label>
                                <select
                                    id="program_interest"
                                    name="program_interest"
                                    class="site-form__input @error('program_interest') site-form__input--error @enderror"
                                    @if($errors->has('program_interest')) aria-invalid="true" aria-describedby="program_interest-error" @endif
                                >
                                    <option value="general" @selected($selectedProgram === 'general')>General support</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->slug }}" @selected($selectedProgram === $program->slug)>{{ $program->title }}</option>
                                    @endforeach
                                </select>
                                @error('program_interest')
                                    <p id="program_interest-error" class="site-form__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="site-form__field site-form__field--full">
                                <label for="message" class="site-form__label">Message <span class="site-form__optional">(optional)</span></label>
                                <p id="message-help" class="site-form__help">Share any preference about timing, amount range, or how you would like to be contacted.</p>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="4"
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

    <x-public.cta-band
        heading="Prefer to give your time or skills?"
        text="Membership, volunteering, and partnership are other ways to strengthen inclusive education with ASNEN."
        :primary-cta="['label' => 'Volunteer', 'url' => route('site.get-involved.volunteer')]"
        :secondary-cta="['label' => 'All pathways', 'url' => route('site.get-involved.index')]"
    />
@endsection
