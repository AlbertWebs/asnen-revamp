@php
    $navLink = function (bool $active): string {
        return $active
            ? 'flex items-center gap-2.5 rounded-lg bg-brand px-2.5 py-2 text-[13px] font-semibold text-white shadow-sm shadow-brand/25'
            : 'flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-[13px] font-medium text-white/68 transition hover:bg-white/[0.07] hover:text-white';
    };

    $sections = [
        [
            'label' => null,
            'items' => [
                [
                    'label' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'active' => request()->routeIs('admin.dashboard'),
                    'can' => true,
                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                ],
            ],
        ],
        [
            'label' => 'Website',
            'items' => [
                ['label' => 'Pages', 'route' => 'admin.pages.index', 'active' => request()->routeIs('admin.pages.*'), 'can' => 'pages.view', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['label' => 'Programs', 'route' => 'admin.programs.index', 'active' => request()->routeIs('admin.programs.*'), 'can' => 'programs.view', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ['label' => 'Articles', 'route' => 'admin.articles.index', 'active' => request()->routeIs('admin.articles.*'), 'can' => 'articles.view', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                ['label' => 'Publications', 'route' => 'admin.publications.index', 'active' => request()->routeIs('admin.publications.*'), 'can' => 'publications.view', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['label' => 'FAQs', 'route' => 'admin.faqs.index', 'active' => request()->routeIs('admin.faqs.*'), 'can' => 'faqs.view', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ],
        ],
        [
            'label' => 'Impact',
            'items' => [
                ['label' => 'Impact Stories', 'route' => 'admin.impact-stories.index', 'active' => request()->routeIs('admin.impact-stories.*'), 'can' => 'impact_stories.view', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                ['label' => 'Impact Metrics', 'route' => 'admin.impact-metrics.index', 'active' => request()->routeIs('admin.impact-metrics.*'), 'can' => 'impact_metrics.view', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['label' => 'Regions', 'route' => 'admin.regions.index', 'active' => request()->routeIs('admin.regions.*'), 'can' => 'regions.view', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
            ],
        ],
        [
            'label' => 'Events',
            'items' => [
                ['label' => 'Events', 'route' => 'admin.events.index', 'active' => request()->routeIs('admin.events.*'), 'can' => 'events.view', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['label' => 'Webinars', 'route' => 'admin.webinars.index', 'active' => request()->routeIs('admin.webinars.*'), 'can' => 'webinars.view', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
            ],
        ],
        [
            'label' => 'People',
            'items' => [
                ['label' => 'Partners', 'route' => 'admin.partners.index', 'active' => request()->routeIs('admin.partners.*'), 'can' => 'partners.view', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['label' => 'Team', 'route' => 'admin.team-members.index', 'active' => request()->routeIs('admin.team-members.*'), 'can' => 'team_members.view', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ],
        ],
        [
            'label' => 'Media',
            'items' => [
                ['label' => 'Media library', 'route' => 'admin.media.index', 'active' => request()->routeIs('admin.media.*'), 'can' => 'media.view', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                [
                    'label' => 'Hero images',
                    'route' => 'admin.hero-images.edit',
                    'active' => request()->routeIs('admin.hero-images.*'),
                    'can' => fn () => auth()->user()?->can('pages.update') || auth()->user()?->can('media.update'),
                    'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zM15 5v4h4',
                ],
                ['label' => 'Galleries', 'route' => 'admin.galleries.index', 'active' => request()->routeIs('admin.galleries.*'), 'can' => 'galleries.view', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
            ],
        ],
        [
            'label' => 'Engagement',
            'items' => [
                ['label' => 'Form submissions', 'route' => 'admin.form-submissions.index', 'active' => request()->routeIs('admin.form-submissions.*'), 'can' => 'form_submissions.view', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['label' => 'Newsletter', 'route' => 'admin.newsletter-subscribers.index', 'active' => request()->routeIs('admin.newsletter-subscribers.*'), 'can' => 'newsletter.view', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                ['label' => 'Announcements', 'route' => 'admin.announcements.index', 'active' => request()->routeIs('admin.announcements.*'), 'can' => 'announcements.view', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                ['label' => 'Donations', 'route' => 'admin.donation-campaigns.index', 'active' => request()->routeIs('admin.donation-campaigns.*'), 'can' => 'donations.view', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ],
        ],
        [
            'label' => 'Settings',
            'items' => [
                ['label' => 'Navigation', 'route' => 'admin.navigation.index', 'active' => request()->routeIs('admin.navigation.*'), 'can' => 'navigation.view', 'icon' => 'M4 6h16M4 12h16M4 18h7'],
                ['label' => 'Redirects', 'route' => 'admin.redirects.index', 'active' => request()->routeIs('admin.redirects.*'), 'can' => 'redirects.view', 'icon' => 'M13 7l5 5m0 0l-5 5m5-5H6'],
                ['label' => 'Site settings', 'route' => 'admin.settings.edit', 'params' => ['group' => 'features'], 'active' => request()->routeIs('admin.settings.*'), 'can' => 'settings.update', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                ['label' => 'Users', 'route' => 'admin.users.index', 'active' => request()->routeIs('admin.users.*'), 'can' => 'users.view', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
            ],
        ],
    ];
@endphp

<div class="flex h-full min-h-0 flex-col">
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-4">
        <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center gap-3 rounded-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand">
            <span class="inline-flex shrink-0 items-center justify-center rounded-lg bg-white p-1 shadow-sm">
                <img src="{{ $siteLogoUrl ?? asset('brand/logo.png') }}" alt="" class="h-7 w-auto" aria-hidden="true">
            </span>
            <span class="min-w-0">
                <span class="block truncate text-sm font-bold tracking-tight text-white">{{ config('app.name', 'ASNEN') }}</span>
                <span class="block text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-white/40">Admin</span>
            </span>
        </a>
    </div>

    <div
        class="relative flex min-h-0 flex-1 flex-col"
        x-data="{
            canScrollDown: false,
            check() {
                const el = this.$refs.nav;
                if (!el) return;
                this.canScrollDown = el.scrollHeight - el.scrollTop - el.clientHeight > 6;
            },
            scrollMore() {
                this.$refs.nav?.scrollBy({ top: 140, behavior: 'smooth' });
            }
        }"
        x-init="
            $nextTick(() => {
                check();
                if (window.ResizeObserver) {
                    new ResizeObserver(() => check()).observe($refs.nav);
                }
                window.addEventListener('resize', () => check());
            })
        "
    >
        <nav
            x-ref="nav"
            @scroll.passive="check()"
            class="admin-sidebar-nav min-h-0 flex-1 overflow-y-auto overscroll-contain px-2.5 py-3"
            aria-label="Admin modules"
        >
            <div class="space-y-1">
                @foreach ($sections as $index => $section)
                    @php
                        $visibleItems = collect($section['items'])->filter(function ($item) {
                            $can = $item['can'] ?? true;
                            if ($can === true) {
                                return true;
                            }
                            if ($can instanceof \Closure) {
                                return (bool) $can();
                            }

                            return auth()->user()?->can($can);
                        });
                    @endphp

                    @if ($visibleItems->isNotEmpty())
                        <div @class([
                            'pt-3' => $index > 0,
                            'border-t border-white/10' => $index > 0,
                        ])>
                            @if (! empty($section['label']))
                                <p class="mb-1.5 px-2.5 text-[0.62rem] font-bold uppercase tracking-[0.18em] text-brand-300/80">
                                    {{ $section['label'] }}
                                </p>
                            @endif

                            <ul class="space-y-0.5" @if(! empty($section['label'])) aria-label="{{ $section['label'] }}" @endif>
                                @foreach ($visibleItems as $item)
                                    <li>
                                        <a
                                            href="{{ route($item['route'], $item['params'] ?? []) }}"
                                            class="{{ $navLink($item['active']) }}"
                                            @if($item['active']) aria-current="page" @endif
                                        >
                                            <svg class="h-4 w-4 shrink-0 opacity-85" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                            </svg>
                                            <span class="truncate">{{ $item['label'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            </div>
        </nav>

        <button
            type="button"
            x-show="canScrollDown"
            x-transition.opacity.duration.200ms
            @click="scrollMore()"
            class="pointer-events-auto absolute bottom-1 left-1/2 z-10 flex h-7 w-7 -translate-x-1/2 items-center justify-center rounded-full bg-charcoal/90 text-brand-300 shadow-md ring-1 ring-white/15 transition hover:text-white"
            aria-label="Scroll for more navigation"
            x-cloak
        >
            <svg class="admin-sidebar-scroll-hint h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </div>

    <div class="shrink-0 border-t border-white/10 p-2.5">
        <a
            href="{{ url('/') }}"
            class="flex items-center gap-2.5 rounded-lg px-2.5 py-2 text-[13px] font-medium text-white/55 transition hover:bg-white/[0.07] hover:text-white"
        >
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to site
        </a>
    </div>
</div>
