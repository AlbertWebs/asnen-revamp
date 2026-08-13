@props([
    'siteName' => 'ASNEN',
    'siteFullName' => 'Africa Special Needs Education Network',
    'contactPhone' => null,
    'contactPhoneSecondary' => null,
    'contactEmail' => null,
    'contactCity' => null,
    'socialLinks' => [],
])

@php
    $phonePrimary = $contactPhone ?: '+254 712 652 621';
    $phoneSecondary = $contactPhoneSecondary ?: '+254 703 906 990';
    $email = $contactEmail ?: 'info@asnenafrica.org';
    $city = $contactCity ?: 'Nairobi, Kenya';

    $fromCms = collect($socialLinks);

    // Live asnenafrica.org links (always show these)
    $social = [
        'twitter' => $fromCms->get('twitter') ?: 'https://twitter.com/AfricanAsnen',
        'facebook' => $fromCms->get('facebook') ?: 'https://www.facebook.com/profile.php?id=100077531126484',
        'linkedin' => $fromCms->get('linkedin') ?: 'https://www.linkedin.com/in/africa-special-needs-education-network-asnen-b31b27237/',
        'instagram' => $fromCms->get('instagram') ?: 'https://www.instagram.com/asnen.ke/',
    ];

    $socialMeta = [
        'twitter' => [
            'label' => 'X',
            'path' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.727-8.835L1.254 2.25H8.08l4.253 5.622L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z',
        ],
        'facebook' => [
            'label' => 'Facebook',
            'path' => 'M22 12a10 10 0 10-11.56 9.87v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.62.77-1.62 1.56V12h2.76l-.44 2.88h-2.32v6.99A10 10 0 0022 12z',
        ],
        'linkedin' => [
            'label' => 'LinkedIn',
            'path' => 'M4.98 3.5A2.5 2.5 0 102.5 6a2.5 2.5 0 002.48-2.5zM3 8.98h3.96V21H3V8.98zM9.5 8.98H13.3v1.64h.05c.53-1 1.83-2.06 3.77-2.06 4.03 0 4.78 2.65 4.78 6.1V21h-3.96v-5.4c0-1.29-.02-2.95-1.8-2.95-1.8 0-2.08 1.4-2.08 2.86V21H9.5V8.98z',
        ],
        'instagram' => [
            'label' => 'Instagram',
            'path' => 'M7 2C4.24 2 2 4.24 2 7v10c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5V7c0-2.76-2.24-5-5-5H7zm10 2a3 3 0 013 3v10a3 3 0 01-3 3H7a3 3 0 01-3-3V7a3 3 0 013-3h10zm-5 3.5A4.5 4.5 0 1016.5 12 4.5 4.5 0 0012 7.5zm0 7.4A2.9 2.9 0 1114.9 12 2.9 2.9 0 0112 14.9zM17.6 6.4a1 1 0 11-1-1 1 1 0 011 1z',
        ],
    ];

    $columns = [
        [
            'title' => 'The Network',
            'links' => [
                ['Home', '/'],
                ['About ASNEN', '/about/who-we-are'],
                ['Vision, Mission & Values', '/about/mission-vision-values'],
                ['Our Story', '/about/our-story'],
                ['Leadership & Governance', '/about/leadership'],
                ['Collaborators', '/about/partners'],
                ['Contact', '/contact'],
            ],
        ],
        [
            'title' => 'What We Do',
            'links' => [
                ['All programmes', '/what-we-do'],
                ['Inclusive Education', '/what-we-do/inclusive-education'],
                ['Caregiver Training', '/what-we-do/caregiver-training'],
                ['Early Identification', '/what-we-do/early-identification-intervention'],
                ['Advocacy', '/what-we-do/disability-awareness-advocacy'],
                ['Community Outreach', '/what-we-do/community-outreach-medical-camps'],
                ['Membership', '/get-involved/membership'],
            ],
        ],
        [
            'title' => 'Get Involved',
            'links' => [
                ['Become a Member', '/get-involved/membership'],
                ['Volunteer', '/get-involved/volunteer'],
                ['Partner With Us', '/get-involved/partner'],
                ['Donate / Support', '/get-involved/donate'],
                ['Privacy Policy', '/privacy'],
                ['Terms of Use', '/terms'],
                ['Accessibility', '/accessibility'],
            ],
        ],
        [
            'title' => 'Impact & Learning',
            'links' => [
                ['Impact Overview', '/impact'],
                ['Success Stories', '/impact/stories'],
                ['Impact Reports', '/impact/reports'],
                ['Events', '/events-learning'],
                ['Webinars', '/events-learning/webinars'],
                ['Resources', '/resources'],
                ['Gallery', '/resources/gallery'],
            ],
        ],
    ];
@endphp

<footer
    id="site-footer"
    class="relative mt-auto overflow-hidden border-t-4 border-brand bg-[#031825] text-white"
    role="contentinfo"
>
    <div class="pointer-events-none absolute inset-0 opacity-[0.06]" aria-hidden="true">
        <svg width="100%" height="100%" preserveAspectRatio="none" viewBox="0 0 100 100">
            <path d="M0 100 Q 50 0 100 100" fill="none" stroke="white" stroke-width=".2" />
            <path d="M0 75 Q 50 -25 100 75" fill="none" stroke="white" stroke-width=".12" />
        </svg>
    </div>

    <div class="relative mx-auto max-w-editorial px-4 pb-10 pt-16 sm:px-6 lg:px-8">
        <div class="mb-14 grid grid-cols-2 gap-10 gap-y-14 md:grid-cols-4 lg:grid-cols-6">
            <div class="col-span-2 md:col-span-4 lg:col-span-2">
                <div class="flex flex-col gap-4">
                    <div>
                        <span class="block text-[11px] font-semibold uppercase tracking-[0.22em] text-white/50">Africa Special Needs Education Network</span>
                        <span class="mt-1 block text-lg font-semibold text-white/90">Registered CBO · Kenya</span>
                        <span class="mt-1 block font-mono text-[0.72rem] tracking-wide text-white/60">CBO No. DAG/CBO/5/4/2022/216</span>
                    </div>

                    <div class="pt-2">
                        <span class="block text-2xl font-bold tracking-tight text-white">{{ $siteName }}</span>
                        <p class="mt-3 max-w-sm text-sm font-medium leading-relaxed text-white/65">
                            A specialised network for inclusive education and lifespan support across Africa - building a homegrown model of inclusion rooted in Ubuntu.
                        </p>
                    </div>

                    <div class="pt-3">
                        <p class="mb-3 text-[10px] font-bold uppercase tracking-widest text-white/70">Follow</p>
                        <div class="flex flex-wrap items-center gap-3" role="list">
                            @foreach($social as $network => $url)
                                <a
                                    href="{{ $url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    role="listitem"
                                    class="inline-flex h-11 min-w-[2.75rem] items-center justify-center gap-2 rounded-xl border border-white/30 bg-white/15 px-3 text-white shadow-sm transition hover:border-gold hover:bg-gold hover:text-charcoal"
                                    aria-label="{{ $socialMeta[$network]['label'] }}"
                                    title="{{ $socialMeta[$network]['label'] }}"
                                >
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="{{ $socialMeta[$network]['path'] }}"/>
                                    </svg>
                                    <span class="hidden text-xs font-semibold sm:inline">{{ $socialMeta[$network]['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @foreach($columns as $column)
                <div class="space-y-6">
                    <h2 class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-white/55">{{ $column['title'] }}</h2>
                    <ul class="space-y-3 text-xs font-semibold text-white/60">
                        @foreach($column['links'] as [$label, $url])
                            <li>
                                <a href="{{ url($url) }}" class="transition-colors hover:text-white">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-2 gap-10 border-t border-white/10 py-10 md:grid-cols-4 lg:grid-cols-6">
            <div class="col-span-2 space-y-6 md:col-span-2">
                <h2 class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-white/55">Contact</h2>
                <address class="space-y-3 text-sm not-italic text-white/70">
                    <p class="leading-relaxed">{{ $city }}</p>
                    <p>
                        <span class="mb-1 block text-[10px] font-bold uppercase tracking-widest text-white/45">Phone</span>
                        <a href="tel:{{ preg_replace('/\s+/', '', $phonePrimary) }}" class="font-semibold text-white/85 transition-colors hover:text-white">{{ $phonePrimary }}</a>
                        <span class="text-white/40"> | </span>
                        <a href="tel:{{ preg_replace('/\s+/', '', $phoneSecondary) }}" class="font-semibold text-white/85 transition-colors hover:text-white">{{ $phoneSecondary }}</a>
                    </p>
                    <p>
                        <span class="mb-1 block text-[10px] font-bold uppercase tracking-widest text-white/45">Email</span>
                        <a href="mailto:{{ $email }}" class="break-all font-semibold text-white/85 transition-colors hover:text-white">{{ $email }}</a>
                    </p>
                </address>
            </div>

            <div class="col-span-2 space-y-6 md:col-span-2 lg:col-span-3 lg:pl-6">
                <h2 class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-white/55">Get in touch</h2>
                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="rounded-xl border border-white/10 bg-white/5 p-5">
                        <span class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-white/45">Primary phone</span>
                        <a
                            href="tel:{{ preg_replace('/\s+/', '', $phonePrimary) }}"
                            class="block text-lg font-extrabold tracking-tight text-white transition-colors hover:text-gold sm:text-xl"
                        >
                            {{ $phonePrimary }}
                        </a>
                    </div>
                    <div class="space-y-3 rounded-xl border border-white/10 bg-white/5 p-5">
                        <span class="mb-2 block text-[10px] font-bold uppercase tracking-widest text-white/45">Take action</span>
                        <div class="grid grid-cols-2 gap-3">
                            <a
                                href="{{ url('/get-involved/membership') }}"
                                class="inline-flex w-full min-w-0 items-center justify-center rounded-lg bg-gold px-3 py-3 text-[10px] font-bold uppercase tracking-[0.12em] text-charcoal transition hover:brightness-95 sm:text-[11px] sm:tracking-[0.18em]"
                            >
                                Join
                            </a>
                            <a
                                href="{{ url('/contact') }}"
                                class="inline-flex w-full min-w-0 items-center justify-center rounded-lg bg-brand px-3 py-3 text-[10px] font-bold uppercase tracking-[0.12em] text-white transition hover:bg-brand-600 sm:text-[11px] sm:tracking-[0.18em]"
                            >
                                Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-2 flex items-end justify-start lg:col-span-1 lg:justify-end">
                <div class="w-full text-left lg:text-right">
                    <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-white/40">
                        &copy; {{ date('Y') }} {{ $siteFullName }}. All rights reserved.
                    </p>
                    <p class="mt-2 text-[10px] font-semibold uppercase tracking-[0.22em] text-white/30">
                        Inclusion for all, in all.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-6 pt-7 text-[10px] font-bold uppercase tracking-[0.35em] text-white/35">
            <a href="{{ url('/privacy') }}" class="transition-colors hover:text-white">Privacy Policy</a>
            <a href="{{ url('/terms') }}" class="transition-colors hover:text-white">Terms of Use</a>
            <a href="{{ url('/cookies') }}" class="transition-colors hover:text-white">Cookie Policy</a>
            <a href="{{ url('/faqs') }}" class="transition-colors hover:text-white">FAQs</a>
            <a href="{{ url('/accessibility') }}" class="transition-colors hover:text-white">Accessibility</a>
            <a href="{{ url('/admin') }}" class="transition-colors hover:text-white">Admin Login</a>
        </div>
    </div>
</footer>
