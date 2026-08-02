<?php

namespace Tests\Concerns;

use Database\Seeders\SettingsSeeder;

trait SeedsPublicSite
{
    protected function seedPublicSiteSettings(): void
    {
        $this->seed(SettingsSeeder::class);
    }
}
