<?php

namespace Tests\Feature\Auth;

use App\Enums\PublishStatus;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }

    public function test_super_admin_can_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRole('Super Admin');

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
    }

    public function test_author_cannot_publish_page_without_permission(): void
    {
        $author = User::factory()->create(['email_verified_at' => now()]);
        $author->assignRole('Author');

        $page = Page::create([
            'title' => 'Draft Page',
            'slug' => 'draft-page',
            'status' => PublishStatus::Draft,
        ]);

        $response = $this->actingAs($author)->post(route('admin.pages.publish', $page));

        $response->assertForbidden();
        $this->assertFalse($page->fresh()->isPublished());
    }
}
