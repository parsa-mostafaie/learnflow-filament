<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions array
        $permissions = [
            'user' => [],
            'instructor' => [
                'manage some thing',
                'manage any courses',
                'make user user',
                'create courses',
                'view all questions',
                'manage any questions',
                'create questions',
            ],
            'manager' => [
                'manage users or activities',
                'change questions state',
                'manage any users',
                'see all courses enrolls',
                'assign many questions to all courses',
                'assign to all courses',
                'delete all questions',
                'force delete all questions',
                'update all questions',
                'restore all questions',
                'make user instructor',
                'enroll to all courses',
                'view all courses',
                'update all courses',
                'delete all courses',
                'restore all courses',
                'create approved questions'
            ],
            'admin' => [
                'make user manager',
                'manage any activities',
                'force delete all courses',
                'impersonate users',
            ],
            'developer' => [
                'edit other users',
                'delete other users',
                'make user admin',
                'prevent from impersonation by users'
            ]
        ];

        // Create permissions
        foreach ($permissions as $role => $rolePermissions) {
            foreach ($rolePermissions as $permission) {
                Permission::create(['name' => $permission]);
            }
        }

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles and assign cumulative permissions
        $cumulativePermissions = [];
        foreach ($permissions as $role => $rolePermissions) {
            // Merge cumulative permissions
            $cumulativePermissions = array_merge($cumulativePermissions, $rolePermissions);

            // Create role
            $createdRole = Role::create(['name' => $role]);

            // Assign cumulative permissions to the role
            $createdRole->givePermissionTo($cumulativePermissions);
        }
    }
}
