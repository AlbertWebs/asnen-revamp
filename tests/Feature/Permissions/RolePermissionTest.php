<?php

namespace Tests\Feature\Permissions;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_super_admin_has_super_admin_role(): void
    {
        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', 'admin@asnenafrica.org')->first();

        $this->assertNotNull($admin);
        $this->assertTrue($admin->hasRole('Super Admin'));
    }

    public function test_author_lacks_pages_publish_permission(): void
    {
        $author = User::factory()->create();
        $author->assignRole('Author');

        $this->assertFalse($author->can('pages.publish'));
        $this->assertTrue($author->can('pages.view'));
        $this->assertTrue($author->can('pages.create'));
        $this->assertTrue($author->can('pages.update'));
    }

    public function test_super_admin_role_has_all_permissions_via_gate(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->assertTrue($user->can('pages.publish'));
        $this->assertTrue($user->can('users.delete'));
        $this->assertTrue($user->can('safeguarding.approve'));
    }

    public function test_expected_roles_exist_after_seeding(): void
    {
        $expected = [
            'Super Admin',
            'Administrator',
            'Editor',
            'Author',
            'Events Manager',
            'Media Manager',
            'Form/CRM Manager',
            'Finance/Donations Manager',
            'Safeguarding Reviewer',
        ];

        foreach ($expected as $roleName) {
            $this->assertTrue(Role::where('name', $roleName)->exists(), "Missing role: {$roleName}");
        }
    }
}
