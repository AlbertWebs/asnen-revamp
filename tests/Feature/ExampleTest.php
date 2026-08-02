<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Enums\VerificationStatus;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsPublicSite;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    use SeedsPublicSite;

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seedPublicSiteSettings();
        Page::create([
            'title' => 'Home',
            'slug' => 'home',
            'template' => 'home',
            'status' => PublishStatus::Published,
            'published_at' => now(),
            'verification_status' => VerificationStatus::Verified,
        ]);

        $this->get('/')->assertOk();
    }
}
