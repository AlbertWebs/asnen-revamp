<?php

namespace Tests\Feature\Admin;

use App\Enums\PublishStatus;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminFormSubmitTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'regions.view', 'regions.create', 'regions.update', 'regions.publish',
            'announcements.view', 'announcements.create', 'announcements.update',
        ] as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $role = Role::findOrCreate('admin', 'web');
        $role->syncPermissions(Permission::all());

        $this->user = User::factory()->create();
        $this->user->assignRole($role);
    }

    public function test_region_save_and_publish_are_separate_submittable_actions(): void
    {
        $region = Region::create([
            'name' => 'Test County',
            'slug' => 'test-county',
            'description' => 'Reach area',
            'latitude' => -1.2,
            'longitude' => 36.8,
            'map_color' => '#0C77BC',
            'reach_radius_km' => 20,
            'status' => PublishStatus::Draft,
        ]);

        $save = $this->actingAs($this->user)->put(route('admin.regions.update', $region), [
            'name' => 'Test County Updated',
            'slug' => 'test-county',
            'description' => 'Updated reach',
            'latitude' => -1.2,
            'longitude' => 36.8,
            'map_color' => '#8CC63F',
            'reach_radius_km' => 25,
            'country' => 'Kenya',
            'impact_label' => 'Outreach',
            'is_featured' => 1,
            'sort_order' => 1,
        ]);

        $save->assertRedirect();
        $this->assertDatabaseHas('regions', [
            'id' => $region->id,
            'name' => 'Test County Updated',
            'map_color' => '#8CC63F',
            'reach_radius_km' => 25,
        ]);

        $publish = $this->actingAs($this->user)->post(route('admin.regions.publish', $region));
        $publish->assertRedirect();
        $region->refresh();
        $this->assertSame(PublishStatus::Published, $region->status);
    }

    public function test_announcement_accepts_site_path_link_url(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.announcements.store'), [
            'message' => 'Join us at the Ubuntu Conference',
            'link_url' => '/events-learning/ubuntu-conference',
            'link_label' => 'Learn more',
            'is_active' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('announcements', [
            'message' => 'Join us at the Ubuntu Conference',
            'link_url' => '/events-learning/ubuntu-conference',
            'is_active' => 1,
        ]);
    }
}
