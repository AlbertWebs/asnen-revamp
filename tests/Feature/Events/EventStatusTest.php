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
        $this->assertTrue($past->isPast());
    }

    public function test_ongoing_events_are_between_start_and_end(): void
    {
        $ongoing = Event::create([
            'title' => 'Live Session',
            'slug' => 'live-session',
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $this->assertTrue($ongoing->isOngoing());
        $this->assertFalse($ongoing->isUpcoming());
        $this->assertFalse($ongoing->isPast());
        $this->assertSame('Ongoing', $ongoing->timingLabel());
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

    public function test_registration_events_appear_on_upcoming_and_detail_pages(): void
    {
        Event::create([
            'title' => 'Why Registration Matters: A Conversation with NCPWD Leadership',
            'slug' => 'why-registration-matters-ncpwd',
            'type' => 'webinar',
            'summary' => 'An online conversation with NCPWD leadership.',
            'starts_at' => '2026-11-23 19:00:00',
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        Event::create([
            'title' => 'Disability Registration Day',
            'slug' => 'disability-registration-day-2026',
            'type' => 'outreach',
            'summary' => 'A day-long registration camp.',
            'starts_at' => '2026-12-05 08:00:00',
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $this->get(route('site.events.upcoming'))
            ->assertOk()
            ->assertSee('Why Registration Matters')
            ->assertSee('Disability Registration Day');

        $this->get(route('site.events.show', 'why-registration-matters-ncpwd'))
            ->assertOk()
            ->assertSee('Register to attend')
            ->assertSee('Reserve your place')
            ->assertSee('Confirm my place')
            ->assertSee('Partner with this initiative')
            ->assertSee('Partner With This Initiative')
            ->assertSee('covering registration and mobilization')
            ->assertSee('500 households')
            ->assertSee('Embakasi')
            ->assertSee('Add to calendar')
            ->assertSee('Part 2: get registered');

        Event::query()->where('slug', 'why-registration-matters-ncpwd')->update(['allow_registration' => false]);

        $this->get(route('site.events.show', 'why-registration-matters-ncpwd'))
            ->assertOk()
            ->assertDontSee('Confirm my place');

        $this->get(route('site.events.show', 'disability-registration-day-2026'))
            ->assertOk()
            ->assertSee('Acorn Special Tutorials, Muhuri Road')
            ->assertSee('Download the partnership brief (PDF)')
            ->assertSee('Part 1: understand why registration matters');

        $this->get(route('site.events.upcoming'))
            ->assertOk()
            ->assertSee('Inclusion for all, in all')
            ->assertSee('Ways to partner');

        $this->get(route('site.events.calendar', 'why-registration-matters-ncpwd'))
            ->assertOk()
            ->assertHeader('content-type', 'text/calendar; charset=utf-8');

        $this->get(route('site.get-involved.partnership-brief'))
            ->assertOk()
            ->assertSee('Presenting Partner');
    }

    public function test_ongoing_events_appear_on_upcoming_page(): void
    {
        Event::create([
            'title' => 'Leaving a mark where it matters',
            'slug' => 'leaving-a-mark-where-it-matters',
            'type' => 'workshop',
            'summary' => 'Eva Naputuni Nyoike in Windhoek.',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $this->get(route('site.events.upcoming'))
            ->assertOk()
            ->assertSee('Ongoing events')
            ->assertSee('Leaving a mark where it matters');

        $this->get(route('site.events.show', 'leaving-a-mark-where-it-matters'))
            ->assertOk()
            ->assertSee('African Continental Curriculum Framework')
            ->assertSee('Windhoek, Namibia')
            ->assertSee('/namibia.mp4')
            ->assertDontSee('Partner With This Initiative');
    }
}
