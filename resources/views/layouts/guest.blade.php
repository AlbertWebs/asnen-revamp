<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin Login' }} | {{ config('app.name', 'ASNEN') }}</title>

        <link rel="icon" href="{{ $siteLogoUrl ?? asset('brand/logo.png') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=work-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
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
    </head>
    <body class="font-sans text-charcoal antialiased bg-sand">
        <a href="#main-content" class="skip-link">Skip to main content</a>

        <div class="relative min-h-screen flex items-center justify-center overflow-hidden p-4 sm:p-8">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(12,119,188,0.08),transparent_55%)]"></div>
                <div class="absolute -top-24 right-[12%] h-64 w-64 rounded-full bg-[radial-gradient(circle,rgba(140,198,63,0.22),transparent_70%)] blur-2xl"></div>
                <div class="absolute -bottom-28 left-[8%] h-72 w-72 rounded-full bg-[radial-gradient(circle,rgba(12,119,188,0.18),transparent_70%)] blur-2xl"></div>
            </div>

            <div class="relative w-full max-w-md">
                <div aria-hidden="true" class="pointer-events-none absolute -inset-6 opacity-60 blur-2xl">
                    <div class="absolute inset-0 bg-[radial-gradient(closest-side,rgba(12,119,188,0.18),transparent_70%)]"></div>
                    <div class="absolute -top-10 -right-10 h-48 w-48 rounded-full bg-[radial-gradient(circle,rgba(140,198,63,0.28),transparent_70%)]"></div>
                    <div class="absolute -bottom-12 -left-12 h-56 w-56 rounded-full bg-[radial-gradient(circle,rgba(74,76,112,0.16),transparent_70%)]"></div>
                </div>

                <div id="main-content" class="relative overflow-hidden rounded-2xl border border-charcoal/10 bg-white shadow-lg" role="main">
                    <div aria-hidden="true" class="h-1.5 bg-gradient-to-r from-brand via-lime to-gold"></div>
                    <div class="p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        <x-public.a11y-toolbar />
        <style>[x-cloak]{display:none!important}</style>
    </body>
</html>
