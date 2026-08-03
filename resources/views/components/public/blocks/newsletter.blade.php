@php
    $c = $content;
    $heading = $c['heading'] ?? 'Stay Connected';
    $intro = $c['intro'] ?? 'Get updates on programmes, events, and ways to support inclusion across Africa. We only send what matters - and you can unsubscribe anytime.';
@endphp

<section class="section-editorial newsletter-section">
    <div class="mx-auto max-w-editorial px-6 lg:px-7">
        <div class="newsletter-layout reveal">
            <div class="newsletter-copy">
                <span class="eyebrow mb-3 block">Newsletter</span>
                <h2 class="newsletter-copy__title">{{ $heading }}</h2>
                <p class="newsletter-copy__intro">{{ $intro }}</p>
                <ul class="newsletter-points" aria-label="What you will receive">
                    <li>Programme and event updates</li>
                    <li>Learning opportunities and webinars</li>
                    <li>Stories from the ASNEN network</li>
                </ul>
            </div>

            <div class="newsletter-panel">
                @if(session('success'))
                    <div class="newsletter-alert newsletter-alert--success" role="status">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="newsletter-alert newsletter-alert--error" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form
                    action="{{ route('site.newsletter.subscribe') }}"
                    method="POST"
                    class="newsletter-form"
                    data-ajax-form
                    novalidate
                >
                    @csrf
                    <div class="newsletter-form__row">
                        <label for="newsletter-name" class="newsletter-form__label">Name <span class="newsletter-form__optional">(optional)</span></label>
                        <input
                            type="text"
                            id="newsletter-name"
                            name="name"
                            value="{{ old('name') }}"
                            autocomplete="name"
                            class="newsletter-form__input"
                        >
                    </div>
                    <div class="newsletter-form__row">
                        <label for="newsletter-email" class="newsletter-form__label">Email</label>
                        <input
                            type="email"
                            id="newsletter-email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            class="newsletter-form__input"
                        >
                    </div>
                    <label class="newsletter-form__consent">
                        <input type="checkbox" name="consent" value="1" required class="newsletter-form__checkbox" @checked(old('consent'))>
                        <span>I consent to receive ASNEN updates by email.</span>
                    </label>
                    <input type="text" name="website" tabindex="-1" autocomplete="off" class="site-form__honeypot" aria-hidden="true">
                    <input type="hidden" name="math_token" value="">
                    <input type="hidden" name="math_answer" value="">
                    <button type="submit" class="btn-primary newsletter-form__submit">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</section>
