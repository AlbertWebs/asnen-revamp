<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Brand
            ['key' => 'brand.name', 'group' => 'brand', 'value' => ['value' => 'Africa Special Needs Education Network'], 'is_public' => true],
            ['key' => 'brand.short_name', 'group' => 'brand', 'value' => ['value' => 'ASNEN'], 'is_public' => true],
            ['key' => 'brand.tagline', 'group' => 'brand', 'value' => ['value' => 'Inclusion for all, in all.'], 'is_public' => true],
            ['key' => 'brand.logo_id', 'group' => 'brand', 'value' => ['value' => ''], 'is_public' => true],

            // Website
            ['key' => 'website.timezone', 'group' => 'website', 'value' => ['value' => 'Africa/Nairobi'], 'is_public' => true],

            // Contact
            ['key' => 'contact.city', 'group' => 'contact', 'value' => ['value' => 'Nairobi, Kenya'], 'is_public' => true],
            ['key' => 'contact.email', 'group' => 'contact', 'value' => ['value' => 'info@asnenafrica.org'], 'is_public' => true],
            ['key' => 'contact.phone_primary', 'group' => 'contact', 'value' => ['value' => '+254 712 652 621'], 'is_public' => true],
            ['key' => 'contact.phone_secondary', 'group' => 'contact', 'value' => ['value' => '+254 703 906 990'], 'is_public' => true],
            ['key' => 'contact.verification_status', 'group' => 'contact', 'value' => ['value' => 'needs_verification'], 'is_public' => false],

            // Social (from asnenafrica.org / known public profiles)
            ['key' => 'social.facebook', 'group' => 'social', 'value' => ['value' => 'https://www.facebook.com/profile.php?id=100077531126484'], 'is_public' => true],
            ['key' => 'social.twitter', 'group' => 'social', 'value' => ['value' => 'https://twitter.com/AfricanAsnen'], 'is_public' => true],
            ['key' => 'social.linkedin', 'group' => 'social', 'value' => ['value' => 'https://www.linkedin.com/in/africa-special-needs-education-network-asnen-b31b27237/'], 'is_public' => true],
            ['key' => 'social.youtube', 'group' => 'social', 'value' => ['value' => ''], 'is_public' => true],
            ['key' => 'social.instagram', 'group' => 'social', 'value' => ['value' => 'https://www.instagram.com/asnen.ke/'], 'is_public' => true],

            // SEO
            [
                'key' => 'seo.default_title',
                'group' => 'seo',
                'value' => ['value' => 'Africa Special Needs Education Network | Inclusion for all, in all.'],
                'is_public' => true,
            ],
            [
                'key' => 'seo.default_description',
                'group' => 'seo',
                'value' => [
                    'value' => 'ASNEN champions inclusive education, disability inclusion, and neurodiversity across Africa through homegrown models of capacity building, advocacy, and community support.',
                ],
                'is_public' => true,
            ],

            // Localization
            ['key' => 'localization.timezone', 'group' => 'localization', 'value' => ['value' => 'Africa/Nairobi'], 'is_public' => true],

            // Features
            ['key' => 'features.easy_read_enabled', 'group' => 'features', 'value' => ['value' => '0'], 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'group' => $setting['group'],
                    'value' => $setting['value'],
                    'is_public' => $setting['is_public'],
                ]
            );
        }

        app(\App\Services\Settings::class)->forget();
    }
}
