<?php

namespace Tests\Feature\Events;

use App\Enums\PublishStatus;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\SeedsPublicSite;
use Tests\TestCase;

class EventStatusTest extends TestCase
{
    use RefreshDatabase;
    use SeedsPublicSite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedPublicSiteSettings();
    }

    public function test_upcoming_events_have_future_starts_at(): void
    {
        $upcoming = Event::create([
            'title' => 'Future Workshop',
            'slug' => 'future-workshop',
            'starts_at' => now()->addWeek(),
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $past = Event::create([
            'title' => 'Past Workshop',
            'slug' => 'past-workshop',
            'starts_at' => now()->subWeek(),
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $this->assertTrue($upcoming->isUpcoming());
        $this->assertFalse($past->isUpcoming());
    }

    public function test_upcoming_events_page_lists_only_future_events(): void
    {
        Event::create([
            'title' => 'Future Event',
            'slug' => 'future-event',
            'starts_at' => now()->addDays(3),
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        Event::create([
            'title' => 'Old Event',
            'slug' => 'old-event',
            'starts_at' => now()->subDays(3),
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $response = $this->get(route('site.events.upcoming'));

        $response->assertOk();
        $response->assertSee('Future Event');
        $response->assertDontSee('Old Event');
    }

    public function test_past_events_page_lists_only_past_events(): void
    {
        Event::create([
            'title' => 'Future Event',
            'slug' => 'future-event-past-page',
            'starts_at' => now()->addDays(3),
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        Event::create([
            'title' => 'Old Event',
            'slug' => 'old-event-past-page',
            'starts_at' => now()->subDays(3),
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $response = $this->get(route('site.events.past'));

        $response->assertOk();
        $response->assertSee('Old Event');
        $response->assertDontSee('Future Event');
    }
}
