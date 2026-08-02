@extends('layouts.public')

@section('title', $page->title.' | '.$siteName)
@section('meta_description', $page->excerpt)

@section('content')
    @php
        $page?->loadMissing('blocks');
        $introHtml = $page?->blocks->firstWhere('type', 'rich_text')?->content['body'] ?? null;
        $isPartner = $formSlug === 'partner';
        $submitLabel = $isPartner ? 'Submit partnership inquiry' : 'Submit application';
        $eyebrow = $isPartner ? 'Collaborate with ASNEN' : 'Get involved';
        $panelTitle = $isPartner ? 'Partnership inquiry' : 'Application form';
        $panelHint = $isPartner
            ? 'Share a short proposal and your organisation details. We will respond by email.'
            : 'Complete the form below and our team will follow up.';
    @endphp

    <section class="impact-hero">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="impact-hero__inner reveal">
                <x-public.breadcrumbs :items="[
                    ['label' => 'Get Involved', 'url' => route('site.get-involved.index')],
                    ['label' => $page->title],
                ]" />
                <span class="eyebrow mt-6 block">{{ $eyebrow }}</span>
                <h1 class="impact-hero__title" style="max-width: 16ch;">{{ $page->title }}</h1>
                @if($page?->excerpt)
                    <p class="impact-hero__excerpt">{{ $page->excerpt }}</p>
                @endif
                @if($introHtml)
                    <div class="impact-hero__body">
                        <x-public.prose :html="$sanitizer->clean($introHtml)" />
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section-editorial volunteer-section">
        <div class="mx-auto max-w-editorial px-6 lg:px-7">
            <div class="mx-auto max-w-2xl reveal">
                <div class="volunteer-panel">
                    <div class="volunteer-panel__head">
                        <h2 class="volunteer-panel__title">{{ $panelTitle }}</h2>
                        <p class="volunteer-panel__hint">{{ $panelHint }}</p>
                    </div>

                    @if($formSlug === 'partner')
                        <x-public.form :action="$formAction" :submit-label="$submitLabel">
                            <div class="site-form__grid">
                                <div class="site-form__field site-form__field--full">
                                    <label for="organisation" class="site-form__label">Organisation name</label>
                                    <input type="text" id="organisation" name="organisation" value="{{ old('organisation') }}" required class="site-form__input @error('organisation') site-form__input--error @enderror">
                                    @error('organisation')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field">
                                    <label for="contact_name" class="site-form__label">Contact person</label>
                                    <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name') }}" required autocomplete="name" class="site-form__input @error('contact_name') site-form__input--error @enderror">
                                    @error('contact_name')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field">
                                    <label for="email" class="site-form__label">Email</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="site-form__input @error('email') site-form__input--error @enderror">
                                    @error('email')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field site-form__field--full">
                                    <label for="phone" class="site-form__label">Phone <span class="site-form__optional">(optional)</span></label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" class="site-form__input @error('phone') site-form__input--error @enderror">
                                    @error('phone')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                                <div class="site-form__field site-form__field--full">
                                    <label for="proposal" class="site-form__label">Partnership proposal</label>
                                    <textarea id="proposal" name="proposal" rows="5" required class="site-form__input site-form__textarea @error('proposal') site-form__input--error @enderror">{{ old('proposal') }}</textarea>
                                    @error('proposal')<p class="site-form__error">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </x-public.form>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
