<?php

namespace Tests\Feature\Admin;

use App\Enums\PublishStatus;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GalleryItemMoveTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['galleries.view', 'galleries.update'] as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $role = Role::findOrCreate('admin', 'web');
        $role->syncPermissions(Permission::all());

        $this->user = User::factory()->create();
        $this->user->assignRole($role);
    }

    public function test_photo_can_be_moved_to_another_album(): void
    {
        $source = Gallery::create([
            'title' => 'General Gallery',
            'slug' => 'general-gallery',
            'status' => PublishStatus::Draft,
        ]);
        $target = Gallery::create([
            'title' => 'Community Moments',
            'slug' => 'community-moments',
            'status' => PublishStatus::Draft,
        ]);
        $asset = MediaAsset::create([
            'disk' => 'public',
            'path' => 'galleries/test.jpg',
            'filename' => 'test.jpg',
            'mime' => 'image/jpeg',
            'size' => 1200,
            'is_private' => false,
        ]);
        $item = GalleryItem::create([
            'gallery_id' => $source->id,
            'media_asset_id' => $asset->id,
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->user)->patchJson(
            route('admin.galleries.items.update', [$source, $item]),
            ['gallery_id' => $target->id]
        );

        $response->assertOk()->assertJsonPath('moved', true);
        $this->assertDatabaseHas('gallery_items', [
            'id' => $item->id,
            'gallery_id' => $target->id,
        ]);
    }
}
