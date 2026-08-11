<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('app.name', 'ASNEN') }}</title>
    <link rel="icon" href="{{ $siteLogoUrl ?? asset('brand/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=work-sans:400,500,600,700,800&display=swap" rel="stylesheet">
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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased bg-sand text-charcoal" data-admin-app x-data="{ sidebarOpen: false }">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-brand focus:px-4 focus:py-2 focus:text-white">
        Skip to main content
    </a>

    <div class="flex min-h-screen">
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-charcoal/50 backdrop-blur-sm lg:hidden"
            x-cloak
            aria-hidden="true"
        ></div>

        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 flex w-[17.5rem] flex-col border-r border-white/10 bg-charcoal text-white transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
            aria-label="Admin navigation"
        >
            @include('admin.partials.sidebar')
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 border-b border-charcoal/10 bg-white/90 backdrop-blur-md">
                <div class="flex h-16 items-center justify-between gap-4 px-4 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            @click="sidebarOpen = !sidebarOpen"
                            class="rounded-xl p-2 text-charcoal/70 transition hover:bg-sand hover:text-charcoal lg:hidden"
                            aria-label="Toggle navigation menu"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div class="min-w-0">
                            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-teal">Admin</p>
                            <h1 class="truncate text-lg font-bold leading-tight text-charcoal">@yield('heading', 'Dashboard')</h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        <a
                            href="{{ url('/') }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="hidden items-center gap-1.5 rounded-xl border border-charcoal/10 bg-white px-3 py-2 text-sm font-medium text-charcoal/80 transition hover:border-brand/30 hover:text-brand sm:inline-flex"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                            View site
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button
                                type="button"
                                @click="open = !open"
                                @keydown.escape.window="open = false"
                                class="flex items-center gap-2 rounded-xl border border-charcoal/10 bg-white px-2.5 py-1.5 text-sm font-medium text-charcoal transition hover:border-brand/30 hover:bg-sand/60"
                                aria-haspopup="true"
                                :aria-expanded="open"
                            >
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-brand text-sm font-semibold text-white">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden max-w-[10rem] truncate sm:inline">{{ auth()->user()->name }}</span>
                                <svg class="hidden h-4 w-4 text-charcoal/40 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div
                                x-show="open"
                                @click.outside="open = false"
                                x-transition
                                x-cloak
                                class="absolute right-0 mt-2 w-52 overflow-hidden rounded-xl border border-charcoal/10 bg-white py-1 shadow-lg"
                                role="menu"
                            >
                                <div class="border-b border-charcoal/5 px-4 py-3">
                                    <p class="truncate text-sm font-semibold text-charcoal">{{ auth()->user()->name }}</p>
                                    <p class="truncate text-xs text-charcoal/50">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-charcoal/80 hover:bg-sand hover:text-charcoal" role="menuitem">Profile</a>
                                <a href="{{ url('/') }}" class="block px-4 py-2.5 text-sm text-charcoal/80 hover:bg-sand hover:text-charcoal sm:hidden" role="menuitem">View site</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full px-4 py-2.5 text-left text-sm text-red-700 hover:bg-red-50" role="menuitem">Log out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div aria-hidden="true" class="h-0.5 bg-gradient-to-r from-brand via-lime to-gold"></div>
            </header>

            @include('admin.partials.flash')
            <div class="px-4 lg:px-8">
                @include('admin.partials.validation-errors')
            </div>

            <main id="main-content" class="flex-1 p-4 lg:p-8" role="main">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <x-public.a11y-toolbar side="right" />
    <x-public.back-to-top />
    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
