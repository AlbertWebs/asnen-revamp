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
        $when = $event->starts_at
            ? $event->starts_at->format('l, j F Y · g:i A')
            : null;
        $where = $event->is_online ? 'Online' : $event->venue;
    @endphp

    <x-public.about-hero
        breadcrumb="Events & learning"
        :breadcrumb-url="route('site.events.index')"
        :current-label="$event->title"
        :title="$event->title"
        title-max="18ch"
        :tagline="$typeLabel"
        :excerpt="$event->summary"
        :primary-cta="$event->isUpcoming() ? ['label' => 'Register', 'url' => '#event-register'] : ['label' => 'All events', 'url' => route('site.events.index')]"
        :secondary-cta="['label' => $event->isUpcoming() ? 'Past events' : 'Upcoming events', 'url' => $event->isUpcoming() ? route('site.events.past') : route('site.events.upcoming')]"
    />

    <x-public.events-subnav :current="$event->type === 'conference' && str_contains($event->slug, 'ubuntu') ? 'ubuntu' : ($event->isUpcoming() ? 'upcoming' : 'past')" />

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
                        @if($when)
                            <div>
                                <dt>When</dt>
                                <dd>{{ $when }}</dd>
                            </div>
                        @endif
                        @if($where)
                            <div>
                                <dt>Where</dt>
                                <dd>{{ $where }}</dd>
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

                    @if($event->isUpcoming())
                        <a href="#event-register" class="btn-primary mt-5 inline-flex w-full justify-center">Register for this event</a>
                    @elseif($event->online_url)
                        <a href="{{ $event->online_url }}" class="btn-secondary mt-5 inline-flex w-full justify-center" target="_blank" rel="noopener noreferrer">Open event link</a>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    @if($event->isUpcoming())
        <section id="event-register" class="section-editorial bg-sand">
            <div class="mx-auto max-w-editorial px-6 lg:px-7">
                <div class="event-register reveal">
                    <div class="event-register__copy">
                        <span class="eyebrow mb-3 block">Registration</span>
                        <h2>Reserve your place</h2>
                        <p class="section-head-row__intro">Share your details and ASNEN will confirm your registration. Required fields are marked.</p>
                    </div>

                    <div class="volunteer-panel">
                        <div class="volunteer-panel__head">
                            <h3 class="volunteer-panel__title">Event registration</h3>
                            <p class="volunteer-panel__hint">We use your details only to manage this event.</p>
                        </div>

                        <x-public.form
                            :action="route('site.events.register', $event->slug)"
                            submit-label="Submit registration"
                            class="site-form"
                        >
                            <div class="site-form__grid">
                                <div class="site-form__field site-form__field--full">
                                    <label for="name" class="site-form__label">Full name</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name" class="site-form__input @error('name') site-form__input--error @enderror">
                                    @error('name')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field">
                                    <label for="email" class="site-form__label">Email</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="site-form__input @error('email') site-form__input--error @enderror">
                                    @error('email')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field">
                                    <label for="phone" class="site-form__label">Phone <span class="site-form__optional">(optional)</span></label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" class="site-form__input @error('phone') site-form__input--error @enderror">
                                    @error('phone')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field site-form__field--full">
                                    <label for="organization" class="site-form__label">Organisation <span class="site-form__optional">(optional)</span></label>
                                    <input type="text" id="organization" name="organization" value="{{ old('organization') }}" class="site-form__input @error('organization') site-form__input--error @enderror">
                                    @error('organization')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field site-form__field--full">
                                    <label for="notes" class="site-form__label">Notes <span class="site-form__optional">(optional)</span></label>
                                    <textarea id="notes" name="notes" rows="4" class="site-form__input site-form__textarea @error('notes') site-form__input--error @enderror">{{ old('notes') }}</textarea>
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
