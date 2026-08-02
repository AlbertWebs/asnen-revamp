<?php

namespace Tests\Feature\Publishing;

use App\Enums\PublishStatus;
use App\Enums\SafeguardingStatus;
use App\Enums\VerificationStatus;
use App\Http\Controllers\PublicSite\Concerns\QueriesPublicContent;
use App\Models\ImpactMetric;
use App\Models\ImpactStory;
use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsPublicSite;
use Tests\TestCase;

class PublishableTest extends TestCase
{
    use RefreshDatabase;
    use SeedsPublicSite;

    public function test_draft_page_is_not_visible_on_public_site(): void
    {
        Page::create([
            'title' => 'Hidden Draft',
            'slug' => 'hidden-draft',
            'status' => PublishStatus::Draft,
        ]);

        $repository = app(PageRepository::class);

        $this->assertNull($repository->findBySlug('hidden-draft'));
    }

    public function test_published_page_is_visible_via_repository(): void
    {
        $page = Page::create([
            'title' => 'Public Page',
            'slug' => 'public-page',
            'status' => PublishStatus::Draft,
        ]);

        $page->publish();

        $found = app(PageRepository::class)->findBySlug('public-page');

        $this->assertNotNull($found);
        $this->assertTrue($found->isPublished());
    }

    public function test_published_home_page_returns_200(): void
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

    public function test_unverified_metric_excluded_even_when_status_forced_published(): void
    {
        ImpactMetric::create([
            'label' => 'Forced stat',
            'value' => '999',
            'numeric_value' => 999,
            'verification_status' => VerificationStatus::NeedsVerification,
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $query = (new class
        {
            use QueriesPublicContent;

            public function metrics()
            {
                return $this->verifiedPublishedMetrics()->get();
            }
        })->metrics();

        $this->assertCount(0, $query);
    }

    public function test_verified_published_metric_appears_in_public_query(): void
    {
        ImpactMetric::create([
            'label' => 'Verified stat',
            'value' => '42',
            'numeric_value' => 42,
            'verification_status' => VerificationStatus::Verified,
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $query = (new class
        {
            use QueriesPublicContent;

            public function metrics()
            {
                return $this->verifiedPublishedMetrics()->get();
            }
        })->metrics();

        $this->assertCount(1, $query);
        $this->assertSame('Verified stat', $query->first()->label);
    }

    public function test_impact_story_with_pending_safeguarding_cannot_publish_safely(): void
    {
        $story = ImpactStory::create([
            'title' => 'Safeguarding Pending',
            'slug' => 'safeguarding-pending',
            'body' => '<p>Story body.</p>',
            'requires_safeguarding' => true,
            'safeguarding_status' => SafeguardingStatus::Pending,
            'status' => PublishStatus::Draft,
        ]);

        $this->assertFalse($story->canPublishSafely());
    }

    public function test_impact_story_with_approved_safeguarding_can_publish_safely(): void
    {
        $story = ImpactStory::create([
            'title' => 'Safeguarding Approved',
            'slug' => 'safeguarding-approved',
            'body' => '<p>Story body.</p>',
            'requires_safeguarding' => true,
            'safeguarding_status' => SafeguardingStatus::Approved,
            'status' => PublishStatus::Draft,
        ]);

        $this->assertTrue($story->canPublishSafely());
    }
}
