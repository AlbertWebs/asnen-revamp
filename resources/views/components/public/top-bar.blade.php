@props([
    'contactPhone' => null,
    'contactPhoneSecondary' => null,
    'contactEmail' => null,
    'socialLinks' => [],
])

@php
    $phonePrimary = $contactPhone ?: '+254 712 652 621';
    $phoneSecondary = $contactPhoneSecondary ?: '+254 703 906 990';
    $email = $contactEmail ?: 'info@asnenafrica.org';

    $fromCms = collect($socialLinks);
    $social = collect([
        'twitter' => $fromCms->get('twitter') ?: 'https://twitter.com/AfricanAsnen',
        'facebook' => $fromCms->get('facebook') ?: 'https://www.facebook.com/profile.php?id=100077531126484',
        'linkedin' => $fromCms->get('linkedin') ?: 'https://www.linkedin.com/in/africa-special-needs-education-network-asnen-b31b27237/',
        'instagram' => $fromCms->get('instagram') ?: 'https://www.instagram.com/asnen.ke/',
    ])->filter();

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
@endphp

<div class="site-topbar" role="region" aria-label="Site contact bar">
    <div class="site-topbar__inner">
        <div class="site-topbar__contact">
            <a href="tel:{{ preg_replace('/\s+/', '', $phonePrimary) }}" class="site-topbar__link">
                <svg class="site-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                </svg>
                <span>{{ $phonePrimary }}</span>
            </a>
            @if($phoneSecondary)
                <span class="site-topbar__sep" aria-hidden="true">|</span>
                <a href="tel:{{ preg_replace('/\s+/', '', $phoneSecondary) }}" class="site-topbar__link site-topbar__link--secondary">
                    <span>{{ $phoneSecondary }}</span>
                </a>
            @endif
            <a href="mailto:{{ $email }}" class="site-topbar__link">
                <svg class="site-topbar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
                <span>{{ $email }}</span>
            </a>
        </div>

        @if($social->isNotEmpty())
            <div class="site-topbar__social" role="list" aria-label="Social media">
                @foreach($social as $network => $url)
                    @continue(! isset($socialMeta[$network]))
                    <a
                        href="{{ $url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        role="listitem"
                        class="site-topbar__social-link"
                        aria-label="{{ $socialMeta[$network]['label'] }}"
                        title="{{ $socialMeta[$network]['label'] }}"
                    >
                        <svg class="site-topbar__social-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="{{ $socialMeta[$network]['path'] }}"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
