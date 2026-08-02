<?php

namespace App\View\Composers;

use App\Http\Controllers\PublicSite\Concerns\QueriesPublicContent;
use App\Models\NavigationMenu;
use App\Services\Settings;
use Illuminate\View\View;

class PublicLayoutComposer
{
    use QueriesPublicContent;

    public function __construct(private Settings $settings) {}

    public function compose(View $view): void
    {
        $primaryMenu = NavigationMenu::where('location', 'primary')->first();
        $footerMenu = NavigationMenu::where('location', 'footer')->first();

        $view->with([
            'siteName' => $this->settings->get('brand.short_name', 'ASNEN'),
            'siteFullName' => $this->settings->get('brand.name', 'Africa Special Needs Education Network'),
            'siteTagline' => $this->settings->get('brand.tagline', 'Inclusion for all, in all.'),
            'defaultSeoTitle' => $this->settings->get('seo.default_title'),
            'defaultSeoDescription' => $this->settings->get('seo.default_description'),
            'contactEmail' => $this->settings->get('contact.email'),
            'contactPhone' => $this->settings->get('contact.phone_primary'),
            'contactPhoneSecondary' => $this->settings->get('contact.phone_secondary'),
            'contactCity' => $this->settings->get('contact.city'),
            'socialLinks' => collect([
                'facebook' => $this->settings->get('social.facebook'),
                'twitter' => $this->settings->get('social.twitter'),
                'instagram' => $this->settings->get('social.instagram'),
                'linkedin' => $this->settings->get('social.linkedin'),
                'youtube' => $this->settings->get('social.youtube'),
            ])->filter(),
            'easyReadEnabled' => filter_var(
                $this->settings->get('features.easy_read_enabled', '0'),
                FILTER_VALIDATE_BOOLEAN
            ),
            'primaryNav' => $primaryMenu?->rootItems()->with(['children' => fn ($q) => $q->where('is_visible', true)])->where('is_visible', true)->get() ?? collect(),
            'footerNav' => $footerMenu?->items()->where('is_visible', true)->get() ?? collect(),
            'globalAnnouncement' => $this->activeAnnouncement(),
        ]);
    }
}
