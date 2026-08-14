<?php

namespace Tests\Feature\Admin;

use App\Enums\PublishStatus;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesGroupingTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_learning_pages_appear_in_admin_pages_group(): void
    {
        foreach ([
            'events-learning' => 'Events & Learning',
            'events-learning-upcoming' => 'Upcoming Events',
            'events-learning-past' => 'Past Events',
            'events-learning-webinars' => 'Webinars',
            'events-learning-ubuntu-conference' => 'Ubuntu Conference',
        ] as $slug => $title) {
            $page = Page::create([
                'title' => $title,
                'slug' => $slug,
                'status' => PublishStatus::Published,
                'published_at' => now(),
            ]);
            if ($page->slug !== $slug) {
                $page->slug = $slug;
                $page->saveQuietly();
            }
        }

        $section = collect(Page::groupedForAdmin(Page::query()->get()))
            ->firstWhere('label', 'Events & Learning');

        $this->assertNotNull($section);
        $this->assertSame(
            [
                'events-learning',
                'events-learning-upcoming',
                'events-learning-past',
                'events-learning-webinars',
                'events-learning-ubuntu-conference',
            ],
            collect($section['rows'])->pluck('page.slug')->all()
        );
        $this->assertSame(0, $section['rows'][0]['depth']);
        $this->assertSame(1, $section['rows'][1]['depth']);
    }
}
