@extends('layouts.public')

@section('title', $event->title.' | '.$siteName)
@section('meta_description', $event->summary ?? ($event->title.' - ASNEN events and learning.'))

@section('content')
    @php
        $typeLabel = match ($event->type) {
            'conference' => 'Conference',
            'workshop' => 'Workshop',
            'webinar' => 'Webinar',
            'outreach' => 'Outreach',
            default => $event->type ? \Illuminate\Support\Str::headline($event->type) : 'Event',
        };
        $profile = $event->pageProfile();
        $allowRegistration = $event->acceptsRegistration();
        $heroPrimary = $profile['primary_cta'] ?? ($allowRegistration ? ['label' => 'Register', 'url' => '#event-register'] : ['label' => 'All events', 'url' => route('site.events.index')]);
        $heroSecondary = $profile['secondary_cta'] ?? ['label' => $event->isUpcoming() ? 'Past events' : 'Upcoming events', 'url' => $event->isUpcoming() ? route('site.events.past') : route('site.events.upcoming')];
        if ($event->isPast()) {
            $heroPrimary = ['label' => 'All events', 'url' => route('site.events.index')];
            $heroSecondary = ['label' => 'Upcoming events', 'url' => route('site.events.upcoming')];
        }
        if (! $allowRegistration && (($heroPrimary['url'] ?? '') === '#event-register')) {
            $heroPrimary = ['label' => 'All events', 'url' => route('site.events.index')];
        }
        if (isset($heroPrimary['url']) && str_starts_with($heroPrimary['url'], '/')) {
            $heroPrimary['url'] = url($heroPrimary['url']);
        }
        if (isset($heroSecondary['url']) && str_starts_with($heroSecondary['url'], '/')) {
            $heroSecondary['url'] = url($heroSecondary['url']);
        }
    @endphp

    <x-public.media-hero
        parent-label="Events & learning"
        :parent-url="route('site.events.index')"
        :current-label="$typeLabel"
        :eyebrow="$event->isPast() ? 'Past event' : ($profile['badge'] ?? $typeLabel)"
        :title="$event->title"
        title-max="18ch"
        :excerpt="$event->summary"
        :primary-cta="$heroPrimary"
        :secondary-cta="$heroSecondary"
        :images="$bannerImages ?? []"
    />

    <x-public.events-subnav :current="$event->type === 'conference' && str_contains($event->slug, 'ubuntu') ? 'ubuntu' : ($event->isUpcoming() ? 'upcoming' : 'past')" />

    @if($profile)
        @include('public.events.partials.featured-layout', [
            'event' => $event,
            'profile' => $profile,
            'typeLabel' => $typeLabel,
            'companionEvent' => $companionEvent ?? null,
            'komolionStory' => $komolionStory ?? null,
            'sanitizer' => $sanitizer,
            'bannerImages' => $bannerImages ?? [],
        ])
    @else
    <section class="section-editorial">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="event-detail reveal">
                <div class="event-detail__copy">
                    <span class="eyebrow mb-3 block">About this event</span>
                    <h2>What to expect</h2>
                    @if($event->body || $event->summary)
                        <div class="event-detail__body">
                            <x-public.prose :html="$sanitizer->clean($event->body ?? $event->summary)" />
                        </div>
                    @endif

                    <div class="event-detail__media">
                        <x-public.media-frame
                            :asset="$event->featuredImage"
                            :alt="$event->featuredImage?->alt ?? $event->title"
                            ratio="16/9"
                            rounded="rounded-2xl"
                            label="Event photo"
                        />
                    </div>
                </div>

                <aside class="event-detail__aside">
                    <p class="who-identity__aside-label">Event details</p>
                    <dl class="event-detail__facts">
                        <div>
                            <dt>Type</dt>
                            <dd>{{ $typeLabel }}</dd>
                        </div>
                        @if($event->starts_at)
                            <div>
                                <dt>When</dt>
                                <dd>{{ $event->starts_at->format('l, j F Y · g:i A') }}</dd>
                            </div>
                        @endif
                        @if($event->is_online || $event->venue)
                            <div>
                                <dt>Where</dt>
                                <dd>{{ $event->is_online ? 'Online' : $event->venue }}</dd>
                            </div>
                        @endif
                        @if($event->timezone)
                            <div>
                                <dt>Timezone</dt>
                                <dd>{{ $event->timezone }}</dd>
                            </div>
                        @endif
                        @if($event->capacity)
                            <div>
                                <dt>Capacity</dt>
                                <dd>{{ number_format($event->capacity) }}</dd>
                            </div>
                        @endif
                    </dl>

                    @if($allowRegistration)
                        <a href="#event-register" class="btn-primary mt-5 inline-flex w-full justify-center">Register for this event</a>
                    @elseif($event->online_url)
                        <a href="{{ $event->online_url }}" class="btn-secondary mt-5 inline-flex w-full justify-center" target="_blank" rel="noopener noreferrer">Open event link</a>
                    @endif

                    @if($relatedPublication?->file)
                        <div class="mt-6 rounded-xl border border-charcoal/10 bg-white p-4">
                            <p class="who-identity__aside-label">Materials</p>
                            <p class="mt-2 text-sm font-semibold text-charcoal">{{ $relatedPublication->title }}</p>
                            @if($relatedPublication->abstract)
                                <p class="mt-1 text-sm leading-relaxed text-charcoal/65">{{ $relatedPublication->abstract }}</p>
                            @endif
                            <a
                                href="{{ route('site.resources.publications.download', $relatedPublication->slug) }}"
                                class="btn-primary mt-4 inline-flex w-full justify-center"
                            >
                                Download presentation
                                @if($relatedPublication->fileSizeLabel())
                                    <span class="ml-2 opacity-80">({{ $relatedPublication->fileSizeLabel() }})</span>
                                @endif
                            </a>
                            <a
                                href="{{ route('site.resources.publications.show', $relatedPublication->slug) }}"
                                class="mt-2 inline-flex w-full justify-center text-sm font-semibold text-brand hover:underline"
                            >
                                View in publications
                            </a>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>
    @endif

    @if($allowRegistration)
        <section id="event-register" class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="event-register reveal">
                    <div class="event-register__copy">
                        <span class="eyebrow mb-3 block">Registration</span>
                        <h2>Reserve your place</h2>
                        <p class="event-register__lede">It takes about a minute. We only use these details to confirm your place and send joining information.</p>
                        <ul class="event-register__points">
                            <li>Confirmation by email</li>
                            <li>Joining link closer to the date</li>
                            <li>No payment required</li>
                        </ul>
                    </div>

                    <div class="event-register__card">
                        <div class="event-register__card-head">
                            <h3 class="event-register__card-title">Register for this event</h3>
                            <p class="event-register__card-hint">Required fields are marked with an asterisk.</p>
                        </div>

                        <x-public.form
                            :action="route('site.events.register', $event->slug)"
                            submit-label="Confirm my place"
                            class="site-form event-register__form"
                            data-ajax-stay="register"
                        >
                            <div class="site-form__grid">
                                <div class="site-form__field">
                                    <label for="name" class="site-form__label">Full name <span class="site-form__req" aria-hidden="true">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Your name" class="site-form__input @error('name') site-form__input--error @enderror">
                                    @error('name')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field">
                                    <label for="email" class="site-form__label">Email <span class="site-form__req" aria-hidden="true">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com" class="site-form__input @error('email') site-form__input--error @enderror">
                                    @error('email')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field">
                                    <label for="phone" class="site-form__label">Phone <span class="site-form__optional">(optional)</span></label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="+254 7XX XXX XXX" class="site-form__input @error('phone') site-form__input--error @enderror">
                                    @error('phone')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field">
                                    <label for="organization" class="site-form__label">Organisation <span class="site-form__optional">(optional)</span></label>
                                    <input type="text" id="organization" name="organization" value="{{ old('organization') }}" placeholder="School, NGO, or workplace" class="site-form__input @error('organization') site-form__input--error @enderror">
                                    @error('organization')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field site-form__field--full">
                                    <label for="notes" class="site-form__label">Anything we should know? <span class="site-form__optional">(optional)</span></label>
                                    <textarea id="notes" name="notes" rows="3" placeholder="Access needs, questions, or who you are attending with" class="site-form__input site-form__textarea @error('notes') site-form__input--error @enderror">{{ old('notes') }}</textarea>
                                    @error('notes')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field site-form__field--full">
                                    <label class="site-form__check">
                                        <input type="checkbox" name="consent_marketing" value="1" @checked(old('consent_marketing'))>
                                        <span>Keep me informed about future ASNEN events and learning opportunities.</span>
                                    </label>
                                </div>
                            </div>
                        </x-public.form>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <x-public.cta-band
        heading="Explore more learning"
        text="Browse upcoming dates, past gatherings, and the Ubuntu Conference series."
        :primary-cta="['label' => 'All events', 'url' => route('site.events.index')]"
        :secondary-cta="['label' => 'Get involved', 'url' => route('site.get-involved.index')]"
    />
@endsection
