<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - {{ config('app.name', 'ASNEN') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=atkinson-hyperlegible:400,700|figtree:400,500,600,700&display=swap" rel="stylesheet">
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
<body class="font-sans antialiased bg-charcoal-100 text-charcoal-900" x-data="{ sidebarOpen: false }">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:rounded-md focus:bg-forest-600 focus:px-4 focus:py-2 focus:text-white">
        Skip to main content
    </a>

    <div class="flex min-h-screen">
        {{-- Mobile overlay --}}
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-charcoal-950/60 lg:hidden"
            aria-hidden="true"
        ></div>

        {{-- Sidebar --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 transform bg-charcoal-900 text-charcoal-100 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0"
            aria-label="Admin navigation"
        >
            @include('admin.partials.sidebar')
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Top bar --}}
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-charcoal-200 bg-white px-4 shadow-sm lg:px-6">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="sidebarOpen = !sidebarOpen"
                        class="rounded-md p-2 text-charcoal-600 hover:bg-charcoal-100 lg:hidden"
                        aria-label="Toggle navigation menu"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-charcoal-900">@yield('heading', 'Admin')</h1>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button
                        type="button"
                        @click="open = !open"
                        @keydown.escape.window="open = false"
                        class="flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-charcoal-700 hover:bg-charcoal-100"
                        aria-haspopup="true"
                        :aria-expanded="open"
                    >
                        <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-forest-700 text-sm font-semibold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </button>
                    <div
                        x-show="open"
                        @click.outside="open = false"
                        x-transition
                        class="absolute right-0 mt-2 w-48 rounded-md border border-charcoal-200 bg-white py-1 shadow-lg"
                        role="menu"
                    >
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-charcoal-700 hover:bg-charcoal-50" role="menuitem">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-charcoal-700 hover:bg-charcoal-50" role="menuitem">Log out</button>
                        </form>
                    </div>
                </div>
            </header>

            @include('admin.partials.flash')

            <main id="main-content" class="flex-1 p-4 lg:p-6" role="main">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <x-public.a11y-toolbar />
    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
