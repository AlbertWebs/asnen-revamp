<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    private const GUARD = 'web';

    /** @var list<string> */
    private const MODULES = [
        'pages',
        'programs',
        'impact_metrics',
        'impact_stories',
        'regions',
        'events',
        'webinars',
        'publications',
        'articles',
        'team_members',
        'partners',
        'testimonials',
        'galleries',
        'faqs',
        'media',
        'forms',
        'form_submissions',
        'newsletter',
        'donations',
        'settings',
        'users',
        'navigation',
        'announcements',
        'redirects',
        'safeguarding',
        'mail_logs',
    ];

    /** @var list<string> */
    private const CONTENT_ACTIONS = [
        'view',
        'create',
        'update',
        'publish',
        'unpublish',
        'archive',
        'restore',
        'delete',
    ];

    /** @var array<string, list<string>> */
    private const MODULE_ACTIONS = [
        'pages' => self::CONTENT_ACTIONS,
        'programs' => self::CONTENT_ACTIONS,
        'impact_metrics' => self::CONTENT_ACTIONS,
        'impact_stories' => [...self::CONTENT_ACTIONS, 'approve_consent'],
        'regions' => self::CONTENT_ACTIONS,
        'events' => self::CONTENT_ACTIONS,
        'webinars' => self::CONTENT_ACTIONS,
        'publications' => self::CONTENT_ACTIONS,
        'articles' => self::CONTENT_ACTIONS,
        'team_members' => self::CONTENT_ACTIONS,
        'partners' => self::CONTENT_ACTIONS,
        'testimonials' => [...self::CONTENT_ACTIONS, 'approve_consent'],
        'galleries' => [...self::CONTENT_ACTIONS, 'approve_consent'],
        'faqs' => self::CONTENT_ACTIONS,
        'media' => ['view', 'create', 'upload', 'update', 'archive', 'restore', 'delete', 'approve_consent'],
        'forms' => ['view', 'create', 'update', 'delete', 'export'],
        'form_submissions' => ['view', 'update', 'export'],
        'newsletter' => ['view', 'export'],
        'donations' => self::CONTENT_ACTIONS,
        'settings' => ['view', 'update', 'manage_settings'],
        'users' => ['view', 'create', 'update', 'delete', 'manage'],
        'navigation' => ['view', 'create', 'update', 'delete'],
        'announcements' => self::CONTENT_ACTIONS,
        'redirects' => ['view', 'create', 'update', 'delete'],
        'safeguarding' => ['approve'],
        'mail_logs' => ['view'],
    ];

    /** @var list<string> */
    private const EDITOR_MODULES = [
        'pages',
        'programs',
        'impact_stories',
        'regions',
        'events',
        'webinars',
        'publications',
        'articles',
        'partners',
        'team_members',
        'faqs',
        'announcements',
        'navigation',
    ];

    /** @var list<string> */
    private const AUTHOR_MODULES = [
        'pages',
        'programs',
        'impact_stories',
        'regions',
        'events',
        'webinars',
        'publications',
        'articles',
        'partners',
        'team_members',
        'faqs',
        'announcements',
    ];

    /** @var list<string> */
    private const SAFEGUARDING_MODULES = [
        'impact_stories',
        'media',
        'testimonials',
        'galleries',
    ];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = $this->createPermissions();

        $this->createRole('Super Admin', $permissions);

        $this->createRole(
            'Administrator',
            array_values(array_filter(
                $permissions,
                fn (string $permission) => $permission !== 'users.delete'
            ))
        );

        $this->createRole('Editor', $this->permissionsForModules(
            self::EDITOR_MODULES,
            ['view', 'create', 'update', 'publish', 'unpublish', 'archive']
        ));

        $this->createRole('Author', $this->permissionsForModules(
            self::AUTHOR_MODULES,
            ['view', 'create', 'update']
        ));

        $this->createRole('Events Manager', array_merge(
            $this->permissionsForModules(['events', 'webinars'], self::CONTENT_ACTIONS),
            $this->permissionsForModules(['forms'], ['view', 'create', 'update', 'delete', 'export']),
            $this->permissionsForModules(['form_submissions'], ['view', 'update', 'export']),
            $this->permissionsForModules(['mail_logs'], ['view'])
        ));

        $this->createRole('Media Manager', $this->permissionsForModules(
            ['media'],
            ['view', 'create', 'upload', 'update', 'archive', 'restore', 'delete', 'approve_consent']
        ));

        $this->createRole('Form/CRM Manager', array_merge(
            $this->permissionsForModules(['forms'], ['view', 'create', 'update', 'delete', 'export']),
            $this->permissionsForModules(['form_submissions'], ['view', 'update', 'export']),
            $this->permissionsForModules(['newsletter'], ['view', 'export']),
            $this->permissionsForModules(['mail_logs'], ['view'])
        ));

        $this->createRole('Finance/Donations Manager', $this->permissionsForModules(
            ['donations'],
            self::CONTENT_ACTIONS
        ));

        $this->createRole('Safeguarding Reviewer', array_merge(
            $this->permissionsForModules(self::SAFEGUARDING_MODULES, ['view']),
            $this->permissionsForModules(self::SAFEGUARDING_MODULES, ['approve_consent']),
            ['safeguarding.approve']
        ));
    }

    /** @return list<string> */
    private function createPermissions(): array
    {
        $names = [];

        foreach (self::MODULES as $module) {
            foreach (self::MODULE_ACTIONS[$module] as $action) {
                $name = "{$module}.{$action}";
                Permission::firstOrCreate(['name' => $name, 'guard_name' => self::GUARD]);
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * @param  list<string>  $permissionNames
     */
    private function createRole(string $roleName, array $permissionNames): Role
    {
        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => self::GUARD]);
        $role->syncPermissions($permissionNames);

        return $role;
    }

    /**
     * @param  list<string>  $modules
     * @param  list<string>  $actions
     * @return list<string>
     */
    private function permissionsForModules(array $modules, array $actions): array
    {
        $permissions = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                if (in_array($action, self::MODULE_ACTIONS[$module] ?? [], true)) {
                    $permissions[] = "{$module}.{$action}";
                }
            }
        }

        return array_values(array_unique($permissions));
    }
}
