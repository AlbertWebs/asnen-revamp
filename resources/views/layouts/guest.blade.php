<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ASNEN') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=atkinson-hyperlegible:400,700|figtree:400,500,600&display=swap" rel="stylesheet" />
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
    <body class="font-sans text-gray-900 antialiased">
        <a href="#main-content" class="skip-link">Skip to main content</a>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div id="main-content" class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg" role="main">
                {{ $slot }}
            </div>
        </div>
        <x-public.a11y-toolbar />
        <style>[x-cloak]{display:none!important}</style>
    </body>
</html>
