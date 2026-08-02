<?php

namespace Tests\Feature\Safeguarding;

use App\Enums\SafeguardingStatus;
use App\Models\ImpactStory;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SafeguardingGateTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_without_safeguarding_requirement_can_publish_safely(): void
    {
        $page = Page::create([
            'title' => 'General Page',
            'slug' => 'general-page',
            'requires_safeguarding' => false,
            'safeguarding_status' => SafeguardingStatus::NotRequired,
        ]);

        $this->assertTrue($page->canPublishSafely());
    }

    public function test_safeguarding_required_content_blocks_until_approved(): void
    {
        $story = ImpactStory::create([
            'title' => 'Child Story',
            'slug' => 'child-story',
            'body' => '<p>Story body.</p>',
            'requires_safeguarding' => true,
            'safeguarding_status' => SafeguardingStatus::Pending,
        ]);

        $this->assertFalse($story->canPublishSafely());

        $story->update(['safeguarding_status' => SafeguardingStatus::Approved]);

        $this->assertTrue($story->fresh()->canPublishSafely());
    }

    public function test_rejected_safeguarding_cannot_publish_safely(): void
    {
        $story = ImpactStory::create([
            'title' => 'Rejected Story',
            'slug' => 'rejected-story',
            'body' => '<p>Story body.</p>',
            'requires_safeguarding' => true,
            'safeguarding_status' => SafeguardingStatus::Rejected,
        ]);

        $this->assertFalse($story->canPublishSafely());
    }
}
