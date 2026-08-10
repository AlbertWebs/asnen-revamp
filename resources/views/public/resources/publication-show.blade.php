@extends('layouts.public')

@section('title', $publication->title.' | '.$siteName)
@section('meta_description', $publication->abstract)

@section('content')
    @php
        $roles = [
            'caregiver' => 'Caregiver / parent',
            'teacher' => 'Teacher / educator',
            'facilitator' => 'Facilitator / trainer',
            'organisation' => 'Organisation / CBO',
            'health_worker' => 'Health worker',
            'other' => 'Other',
        ];
        $isToolkitLike = in_array($publication->category, ['toolkit', 'guide'], true);
        $backUrl = $isToolkitLike
            ? route('site.resources.toolkits')
            : route('site.resources.publications');
        $backLabel = $isToolkitLike
            ? 'Back to Toolkits and Guides'
            : 'Back to Publications';
        $requestNoun = $isToolkitLike ? 'toolkit' : 'publication';
        $requestCta = 'Request this file';
        $requestTitle = $isToolkitLike ? 'Request this toolkit' : 'Request this publication';
        $requestHint = $isToolkitLike
            ? 'Tell us a little about yourself and how you will use it. ASNEN will follow up with access details.'
            : 'Tell us a little about yourself. ASNEN will follow up with the file or next steps.';
        $requestSubmit = $isToolkitLike ? 'Submit toolkit request' : 'Submit file request';
        $useLabel = $isToolkitLike
            ? 'How you plan to use the toolkit'
            : 'How you plan to use this publication';
        $openRequestForm = $errors->any() || filled(old('name')) || filled(old('email'));
    @endphp

    <section class="impact-hero">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="impact-hero__inner reveal">
                <x-public.breadcrumbs :items="[
                    ['label' => 'Resources', 'url' => route('site.resources.index')],
                    ['label' => 'Publications', 'url' => route('site.resources.publications')],
                    ['label' => $publication->title],
                ]" />
                <span class="eyebrow mt-6 block">{{ $publication->categoryLabel() }}</span>
                <h1 class="impact-hero__title" style="max-width: 20ch;">{{ $publication->title }}</h1>
                @if($publication->year)
                    <p class="impact-hero__excerpt">{{ $publication->year }}</p>
                @endif
            </div>
        </div>
    </section>

    <x-public.section>
        <div class="pub-show reveal">
            <aside class="pub-show__cover">
                <x-public.media-frame
                    :asset="$publication->cover"
                    :alt="$publication->cover?->alt ?? $publication->title"
                    ratio="3/4"
                    rounded="rounded-none"
                    label="Publication cover"
                />
            </aside>

            <div class="pub-show__main">
                @if($publication->abstract)
                    <p class="pub-show__abstract">{{ $publication->abstract }}</p>
                @endif

                @if($publication->file)
                    <div class="pub-show__actions">
                        <a href="{{ route('site.resources.publications.download', $publication->slug) }}" class="btn-primary">
                            Download PDF
                            @if($publication->fileSizeLabel())
                                <span class="ml-2 opacity-90">({{ $publication->fileSizeLabel() }})</span>
                            @endif
                        </a>
                        <p class="pub-show__downloads">{{ number_format($publication->download_count) }} downloads</p>
                    </div>
                @elseif($canRequestFile)
                    <div
                        class="file-request"
                        id="request-file"
                        x-data="{
                            open: {{ $openRequestForm ? 'true' : 'false' }},
                            init() {
                                if (window.location.hash === '#request-file') {
                                    this.$nextTick(() => {
                                        this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                    });
                                }
                            },
                            show() {
                                this.open = true;
                                if (history.replaceState) {
                                    history.replaceState(null, '', '#request-file');
                                }
                                this.$nextTick(() => this.focusForm());
                            },
                            hide() {
                                this.open = false;
                                if (history.replaceState && window.location.hash === '#request-file') {
                                    history.replaceState(null, '', window.location.pathname + window.location.search);
                                }
                            },
                            focusForm() {
                                this.$refs.requestPanel?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                this.$refs.requestPanel?.querySelector('input:not([type=hidden])')?.focus({ preventScroll: true });
                            }
                        }"
                    >
                        <template x-if="!open">
                            <div class="file-request__teaser">
                                <div class="file-request__teaser-copy">
                                    <p class="file-request__badge">Available on request</p>
                                    <h2 class="file-request__teaser-title">Need this {{ $requestNoun }}?</h2>
                                    <p class="file-request__teaser-text">
                                        Share a short request and ASNEN will follow up with access details by email.
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    class="btn-primary file-request__cta"
                                    @click="show()"
                                    aria-expanded="false"
                                    aria-controls="file-request-panel"
                                >
                                    {{ $requestCta }}
                                </button>
                            </div>
                        </template>

                        <template x-if="open">
                            <div
                                class="file-request__panel"
                                id="file-request-panel"
                                x-ref="requestPanel"
                                role="region"
                                aria-labelledby="file-request-heading"
                            >
                            <div class="file-request__panel-head">
                                <div>
                                    <p class="file-request__badge">Request form</p>
                                    <h2 class="file-request__panel-title" id="file-request-heading">{{ $requestTitle }}</h2>
                                    <p class="file-request__panel-hint">{{ $requestHint }}</p>
                                </div>
                                <button type="button" class="file-request__close" @click="hide()" aria-label="Close request form">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                            <ol class="file-request__steps" aria-label="What happens next">
                                <li><span class="file-request__step-num" aria-hidden="true">1</span> Send your details</li>
                                <li><span class="file-request__step-num" aria-hidden="true">2</span> ASNEN reviews the request</li>
                                <li><span class="file-request__step-num" aria-hidden="true">3</span> We reply by email</li>
                            </ol>

                            <x-public.form
                                :action="route('site.resources.publications.request', $publication->slug)"
                                :submit-label="$requestSubmit"
                                :show-submit="false"
                                class="site-form file-request__form"
                            >
                                <input type="hidden" name="publication_slug" value="{{ $publication->slug }}">
                                <input type="hidden" name="publication_title" value="{{ $publication->title }}">

                                <div class="site-form__grid">
                                    <div class="site-form__field site-form__field--full">
                                        <label for="name" class="site-form__label">Full name</label>
                                        <input
                                            id="name"
                                            name="name"
                                            type="text"
                                            required
                                            autocomplete="name"
                                            value="{{ old('name') }}"
                                            class="site-form__input @error('name') site-form__input--error @enderror"
                                            aria-describedby="{{ $errors->has('name') ? 'name-error' : '' }}"
                                        >
                                        @error('name')
                                            <p id="name-error" class="site-form__error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="site-form__field">
                                        <label for="email" class="site-form__label">Email</label>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            required
                                            autocomplete="email"
                                            value="{{ old('email') }}"
                                            class="site-form__input @error('email') site-form__input--error @enderror"
                                            aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                                        >
                                        @error('email')
                                            <p id="email-error" class="site-form__error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="site-form__field">
                                        <label for="phone" class="site-form__label">Phone <span class="site-form__optional">(optional)</span></label>
                                        <input
                                            id="phone"
                                            name="phone"
                                            type="tel"
                                            autocomplete="tel"
                                            value="{{ old('phone') }}"
                                            class="site-form__input @error('phone') site-form__input--error @enderror"
                                            aria-describedby="{{ $errors->has('phone') ? 'phone-error' : '' }}"
                                        >
                                        @error('phone')
                                            <p id="phone-error" class="site-form__error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="site-form__field">
                                        <label for="role" class="site-form__label">Your role</label>
                                        <select
                                            id="role"
                                            name="role"
                                            required
                                            class="site-form__input @error('role') site-form__input--error @enderror"
                                            aria-describedby="{{ $errors->has('role') ? 'role-error' : '' }}"
                                        >
                                            <option value="" disabled @selected(! old('role'))>Select a role</option>
                                            @foreach($roles as $value => $label)
                                                <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <p id="role-error" class="site-form__error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="site-form__field">
                                        <label for="organisation" class="site-form__label">Organisation / school <span class="site-form__optional">(optional)</span></label>
                                        <input
                                            id="organisation"
                                            name="organisation"
                                            type="text"
                                            autocomplete="organization"
                                            value="{{ old('organisation') }}"
                                            class="site-form__input @error('organisation') site-form__input--error @enderror"
                                            aria-describedby="{{ $errors->has('organisation') ? 'organisation-error' : '' }}"
                                        >
                                        @error('organisation')
                                            <p id="organisation-error" class="site-form__error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="site-form__field">
                                        <label for="location" class="site-form__label">Location <span class="site-form__optional">(optional)</span></label>
                                        <input
                                            id="location"
                                            name="location"
                                            type="text"
                                            placeholder="County, city, or country"
                                            value="{{ old('location') }}"
                                            class="site-form__input @error('location') site-form__input--error @enderror"
                                            aria-describedby="{{ $errors->has('location') ? 'location-error' : '' }}"
                                        >
                                        @error('location')
                                            <p id="location-error" class="site-form__error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="site-form__field">
                                        <label for="quantity" class="site-form__label">Copies needed <span class="site-form__optional">(optional)</span></label>
                                        <input
                                            id="quantity"
                                            name="quantity"
                                            type="number"
                                            min="1"
                                            max="500"
                                            inputmode="numeric"
                                            value="{{ old('quantity') }}"
                                            class="site-form__input @error('quantity') site-form__input--error @enderror"
                                            aria-describedby="{{ $errors->has('quantity') ? 'quantity-error' : '' }}"
                                        >
                                        @error('quantity')
                                            <p id="quantity-error" class="site-form__error">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="site-form__field site-form__field--full">
                                        <label for="message" class="site-form__label">{{ $useLabel }} <span class="site-form__optional">(optional)</span></label>
                                        <textarea
                                            id="message"
                                            name="message"
                                            rows="3"
                                            class="site-form__input site-form__textarea @error('message') site-form__input--error @enderror"
                                            aria-describedby="{{ $errors->has('message') ? 'message-error' : '' }}"
                                            placeholder="Classroom, caregiver group, training workshop…"
                                        >{{ old('message') }}</textarea>
                                        @error('message')
                                            <p id="message-error" class="site-form__error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="file-request__actions">
                                    <button type="button" class="btn-secondary" @click="hide()">Cancel</button>
                                    <button type="submit" class="btn-primary">{{ $requestSubmit }}</button>
                                </div>
                                <p class="file-request__privacy">
                                    We only use these details to respond to your request.
                                </p>
                            </x-public.form>
                            </div>
                        </template>
                    </div>
                @endif

                <p class="pub-show__back">
                    <a href="{{ $backUrl }}" class="font-mono text-[0.7rem] font-bold uppercase tracking-wider text-brand hover:underline">← {{ $backLabel }}</a>
                </p>
            </div>
        </div>
    </x-public.section>
@endsection
