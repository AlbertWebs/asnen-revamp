<?php

namespace Tests\Feature\Redirects;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsPublicSite;
use Tests\TestCase;

class ReportsPublicationsMergeTest extends TestCase
{
    use RefreshDatabase;
    use SeedsPublicSite;

    public function test_impact_reports_redirects_to_publications(): void
    {
        $this->seedPublicSiteSettings();

        $this->get('/impact/reports')
            ->assertRedirect('/resources/publications')
            ->assertStatus(301);
    }

    public function test_publications_page_renders(): void
    {
        $this->seedPublicSiteSettings();

        $this->get('/resources/publications')
            ->assertOk()
            ->assertSee('Reports & Publications');
    }
}
