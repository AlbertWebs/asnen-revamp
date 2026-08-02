<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        $primary = NavigationMenu::updateOrCreate(
            ['location' => 'primary'],
            ['name' => 'Primary Navigation']
        );

        $footer = NavigationMenu::updateOrCreate(
            ['location' => 'footer'],
            ['name' => 'Footer Navigation']
        );

        NavigationItem::where('menu_id', $primary->id)->forceDelete();
        NavigationItem::where('menu_id', $footer->id)->forceDelete();

        $this->seedPrimaryMenu($primary);
        $this->seedFooterMenu($footer);
    }

    private function seedPrimaryMenu(NavigationMenu $menu): void
    {
        $order = 0;

        $this->item($menu, null, 'Home', '/', ++$order);

        $about = $this->item($menu, null, 'About', '/about', ++$order);
        $aboutChildren = [
            ['Who We Are', '/about/who-we-are'],
            ['Mission, Vision & Values', '/about/mission-vision-values'],
            ['Our Story', '/about/our-story'],
            ['Leadership & Team', '/about/leadership'],
            ['Governance', '/about/governance'],
            ['Partners', '/about/partners'],
        ];
        $childOrder = 0;
        foreach ($aboutChildren as [$label, $url]) {
            $this->item($menu, $about->id, $label, $url, ++$childOrder);
        }

        $whatWeDo = $this->item($menu, null, 'What We Do', '/what-we-do', ++$order);
        $programChildren = [
            ['Inclusive Education', '/what-we-do/inclusive-education'],
            ['Caregiver Training', '/what-we-do/caregiver-training'],
            ['Early Identification & Intervention', '/what-we-do/early-identification-intervention'],
            ['Disability Awareness & Advocacy', '/what-we-do/disability-awareness-advocacy'],
            ['Social Inclusion', '/what-we-do/social-inclusion'],
            ['Research, Policy & Partnerships', '/what-we-do/research-policy-partnerships'],
            ['Community Outreach & Medical Camps', '/what-we-do/community-outreach-medical-camps'],
        ];
        $childOrder = 0;
        foreach ($programChildren as [$label, $url]) {
            $this->item($menu, $whatWeDo->id, $label, $url, ++$childOrder);
        }

        $impact = $this->item($menu, null, 'Impact', '/impact', ++$order);
        $impactChildren = [
            ['Impact Overview', '/impact'],
            ['Komolion Story', '/impact/komolion'],
            ['Success Stories', '/impact/stories'],
            ['Impact Reports', '/impact/reports'],
            ['Impact by Region', '/impact/regions'],
        ];
        $childOrder = 0;
        foreach ($impactChildren as [$label, $url]) {
            $this->item($menu, $impact->id, $label, $url, ++$childOrder);
        }

        $events = $this->item($menu, null, 'Events & Learning', '/events-learning', ++$order);
        $eventsChildren = [
            ['Upcoming Events', '/events-learning/upcoming'],
            ['Past Events', '/events-learning/past'],
            ['Webinars', '/events-learning/webinars'],
            ['Ubuntu Conference', '/events-learning/ubuntu-conference'],
        ];
        $childOrder = 0;
        foreach ($eventsChildren as [$label, $url]) {
            $this->item($menu, $events->id, $label, $url, ++$childOrder);
        }

        $resources = $this->item($menu, null, 'Resources', '/resources', ++$order);
        $resourcesChildren = [
            ['Reports & Publications', '/resources/publications'],
            ['Toolkits and Guides', '/resources/toolkits'],
            ['Videos / Webinar Library', '/resources/webinars'],
            ['News & Insights', '/resources/news'],
            ['Gallery', '/resources/gallery'],
        ];
        $childOrder = 0;
        foreach ($resourcesChildren as [$label, $url]) {
            $this->item($menu, $resources->id, $label, $url, ++$childOrder);
        }

        $getInvolved = $this->item($menu, null, 'Get Involved', '/get-involved', ++$order);
        $involvedChildren = [
            ['Become a Member', '/get-involved/membership'],
            ['Volunteer', '/get-involved/volunteer'],
            ['Partner With Us', '/get-involved/partner'],
            ['Donate / Support a Program', '/get-involved/donate'],
        ];
        $childOrder = 0;
        foreach ($involvedChildren as [$label, $url]) {
            $this->item($menu, $getInvolved->id, $label, $url, ++$childOrder);
        }

        $this->item($menu, null, 'Contact', '/contact', ++$order);
    }

    private function seedFooterMenu(NavigationMenu $menu): void
    {
        $order = 0;
        $links = [
            ['About ASNEN', '/about/who-we-are'],
            ['What We Do', '/what-we-do'],
            ['Impact', '/impact'],
            ['Get Involved', '/get-involved'],
            ['Contact', '/contact'],
            ['Accessibility', '/accessibility'],
            ['Privacy Policy', '/privacy'],
            ['Terms of Use', '/terms'],
            ['Cookie Policy', '/cookies'],
            ['FAQs', '/faqs'],
            ['Safeguarding', '/safeguarding'],
        ];

        foreach ($links as [$label, $url]) {
            $this->item($menu, null, $label, $url, ++$order);
        }
    }

    private function item(
        NavigationMenu $menu,
        ?int $parentId,
        string $label,
        string $url,
        int $sortOrder
    ): NavigationItem {
        return NavigationItem::create([
            'menu_id' => $menu->id,
            'parent_id' => $parentId,
            'label' => $label,
            'url' => $url,
            'sort_order' => $sortOrder,
            'is_visible' => true,
        ]);
    }
}
