<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:ital,wght@0,400;0,700;1,400;1,700&family=Source+Serif+4:ital,opsz,wght@0,8..60,500;0,8..60,600;0,8..60,700;1,8..60,500;1,8..60,600&family=Work+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">

    <link rel="icon" href="{{ $siteLogoUrl ?? asset('brand/logo.png') }}" type="image/png">
    <title>@yield('title', $defaultSeoTitle ?? $siteName)</title>
    <meta name="description" content="@yield('meta_description', $defaultSeoDescription ?? $siteTagline)">
    <meta name="color-scheme" content="light dark">

    <script>
        (function () {
            try {
                var raw = localStorage.getItem('asnen_a11y_prefs_v1');
                if (!raw) return;
                var p = JSON.parse(raw);
                var root = document.documentElement;
                var map = {
                    text: { lg: 'a11y-text-lg', xl: 'a11y-text-xl', '2xl': 'a11y-text-2xl' },
                    spacing: { relaxed: 'a11y-spacing-relaxed', loose: 'a11y-spacing-loose' },
                    contrast: { high: 'a11y-contrast-high', dark: 'a11y-contrast-dark' }
                };
                if (p.text && map.text[p.text]) root.classList.add(map.text[p.text]);
                if (p.spacing && map.spacing[p.spacing]) root.classList.add(map.spacing[p.spacing]);
                if (p.contrast && map.contrast[p.contrast]) root.classList.add(map.contrast[p.contrast]);
                var bools = {
                    readableFont: 'a11y-readable-font',
                    underlineLinks: 'a11y-underline-links',
                    focusStrong: 'a11y-focus-strong',
                    highlightHeadings: 'a11y-highlight-headings',
                    reduceMotion: 'a11y-reduce-motion',
                    largeTargets: 'a11y-large-targets',
                    bigCursor: 'a11y-big-cursor',
                    grayscale: 'a11y-grayscale',
                    lowSaturation: 'a11y-low-saturation',
                    hideImages: 'a11y-hide-images',
                    readableWidth: 'a11y-readable-width',
                    textLeft: 'a11y-text-left',
                    pauseMedia: 'a11y-pause-media'
                };
                Object.keys(bools).forEach(function (k) { if (p[k]) root.classList.add(bools[k]); });
            } catch (e) {}
        })();
    </script>

    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title')) ?: ($defaultSeoTitle ?? $siteName))">
    <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('meta_description')) ?: ($defaultSeoDescription ?? $siteTagline))">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function () {
            function syncSiteChrome() {
                var ann = document.querySelector('[aria-label="Site contact bar"], [aria-label="Site announcement"]');
                var header = document.getElementById('site-header');
                var h = (ann ? ann.offsetHeight : 0) + (header ? header.offsetHeight : 0);
                if (h) document.documentElement.style.setProperty('--site-chrome', h + 'px');
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', syncSiteChrome);
            } else {
                syncSiteChrome();
            }
            window.addEventListener('resize', syncSiteChrome);
        })();
    </script>
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'NGO',
        'name' => $siteFullName ?? 'Africa Special Needs Education Network',
        'alternateName' => $siteName ?? 'ASNEN',
        'url' => url('/'),
        'email' => $contactEmail ?? null,
        'telephone' => $contactPhone ?? null,
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $contactCity ?? 'Nairobi',
            'addressCountry' => 'KE',
        ],
        'slogan' => $siteTagline ?? 'Inclusion for all, in all.',
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_APOS) !!}
    </script>
    @stack('head')
</head>
<body
    class="flex min-h-screen flex-col bg-ivory text-charcoal"
    data-math-challenge-url="{{ route('site.forms.math-challenge') }}"
>
    <x-public.a11y-skip-links />

    <x-public.top-bar
        :contact-phone="$contactPhone ?? null"
        :contact-phone-secondary="$contactPhoneSecondary ?? null"
        :contact-email="$contactEmail ?? null"
        :social-links="$socialLinks ?? []"
    />

    <header
        id="site-header"
        class="site-header sticky top-0 z-40"
        x-data="{ mobileOpen: false, openSection: null, scrolled: window.scrollY > 12 }"
        :class="{ 'is-scrolled': scrolled, 'is-mobile-open': mobileOpen }"
        @scroll.window.throttle.50ms="scrolled = window.scrollY > 12"
        @keydown.escape.window="mobileOpen = false"
    >
        <div class="site-header__bar">
            <div class="site-header__inner">
                <a href="{{ route('site.home') }}" class="site-header__brand">
                    <img
                        src="{{ $siteLogoUrl ?? asset('brand/logo.png') }}"
                        alt="{{ $siteFullName ?? 'Africa Special Needs Education Network' }}"
                        class="site-header__logo"
                        width="206"
                        height="138"
                    >
                </a>

                <nav class="primary-nav hidden lg:flex" aria-label="Primary">
                    @foreach($primaryNav as $item)
                        @if($item->children->isNotEmpty())
                            <x-public.mega-nav-item :item="$item" />
                        @else
                            @php $isActive = $item->isActive(); @endphp
                            <div class="nav-item relative flex items-center">
                                <a
                                    href="{{ $item->url }}"
                                    class="nav-link-editorial {{ $isActive ? 'is-active' : '' }}"
                                    @if($isActive) aria-current="page" @endif
                                >
                                    <span>{{ $item->label }}</span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </nav>

                <div class="site-header__actions">
                    <a href="{{ route('site.get-involved.membership') }}" class="btn-gold site-header__cta hidden sm:inline-flex">Become a member</a>
                    <button
                        type="button"
                        class="site-header__menu-btn lg:hidden"
                        @click="mobileOpen = !mobileOpen"
                        :aria-expanded="mobileOpen.toString()"
                        aria-controls="mobile-menu"
                        :aria-label="mobileOpen ? 'Close menu' : 'Open menu'"
                    >
                        <span class="site-header__burger" aria-hidden="true" :class="{ 'is-open': mobileOpen }">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <div
            id="mobile-menu"
            class="site-header__mobile lg:hidden"
            x-show="mobileOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            @click.outside="mobileOpen = false"
        >
            <nav class="site-header__mobile-nav" aria-label="Mobile primary">
                @foreach($primaryNav as $item)
                    @if($item->children->isNotEmpty())
                        @php $sectionActive = $item->isActive(); @endphp
                        <div class="site-header__mobile-section">
                            <button
                                type="button"
                                class="site-header__mobile-toggle {{ $sectionActive ? 'is-active' : '' }}"
                                @click="openSection = openSection === {{ $item->id }} ? null : {{ $item->id }}"
                                :aria-expanded="(openSection === {{ $item->id }}).toString()"
                            >
                                <span>{{ $item->label }}</span>
                                <svg class="h-4 w-4 transition-transform duration-200" :class="openSection === {{ $item->id }} && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="site-header__mobile-children" x-show="openSection === {{ $item->id }}" x-cloak>
                                <a href="{{ $item->url }}" class="site-header__mobile-child site-header__mobile-child--overview">Overview</a>
                                @foreach($item->children as $child)
                                    @php $childActive = $item->childIsActive($child->url); @endphp
                                    <a
                                        href="{{ $child->url }}"
                                        class="site-header__mobile-child {{ $childActive ? 'is-active' : '' }}"
                                        @if($childActive) aria-current="page" @endif
                                    >{{ $child->label }}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        @php $isActive = $item->isActive(); @endphp
                        <a
                            href="{{ $item->url }}"
                            class="site-header__mobile-link {{ $isActive ? 'is-active' : '' }}"
                            @if($isActive) aria-current="page" @endif
                        >{{ $item->label }}</a>
                    @endif
                @endforeach
                <div class="site-header__mobile-ctas">
                    <a href="{{ route('site.get-involved.membership') }}" class="btn-gold inline-flex w-full justify-center">Become a member</a>
                </div>
            </nav>
        </div>
    </header>

    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    <x-public.site-footer
        :site-name="$siteName"
        :site-full-name="$siteFullName"
        :contact-phone="$contactPhone"
        :contact-phone-secondary="$contactPhoneSecondary"
        :contact-email="$contactEmail"
        :contact-city="$contactCity"
        :social-links="$socialLinks"
    />

    <x-public.a11y-toolbar />
    <x-public.back-to-top />
    <x-public.cookie-consent />

    <div id="math-captcha-modal" class="math-captcha" hidden>
        <div class="math-captcha__backdrop" data-math-cancel tabindex="-1"></div>
        <div class="math-captcha__dialog" role="dialog" aria-modal="true" aria-labelledby="math-captcha-title">
            <h2 id="math-captcha-title" class="math-captcha__title">Quick check</h2>
            <p class="math-captcha__intro">Solve this short sum so we know you are human.</p>
            <p class="math-captcha__question" data-math-question aria-live="polite"></p>
            <label for="math-captcha-answer" class="math-captcha__label">Your answer</label>
            <input id="math-captcha-answer" type="number" inputmode="numeric" class="math-captcha__input" data-math-answer autocomplete="off">
            <p class="math-captcha__error" data-math-error hidden></p>
            <div class="math-captcha__actions">
                <button type="button" class="btn-secondary" data-math-cancel>Cancel</button>
                <button type="button" class="btn-primary" data-math-confirm>Confirm &amp; send</button>
            </div>
        </div>
    </div>

    @stack('scripts')
    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
