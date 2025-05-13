<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $developerRole = Role::create(['role_name' => 'developer', 'role_description' => 'Developer mode']);
        $editorRole = Role::create(['role_name' => 'editor', 'role_description' => 'Editor mode']);
        $userRole = Role::create(['role_name' => 'user', 'role_description' => 'User Mode']);

        // Create Permissions (matching your routes)
        $permissions = [
            'dashboard-view',
            'master-jabatan-guru-index',
            'master-jabatan-guru-list',
            'master-jabatan-guru-store',
            'master-jabatan-guru-show',
            'master-jabatan-guru-update',
            'master-jabatan-guru-update-status-multiple',
            'master-guru-index',
            'master-guru-list',
            'master-guru-create',
            'master-guru-store',
            'master-guru-show',
            'master-guru-edit',
            'master-guru-update',
            'system-log-activity-index',
            'system-log-activity-list',
            'system-log-activity-clear',
            'rbac-role-index',
            'rbac-role-list',
            'rbac-role-store',
            'rbac-role-show',
            'rbac-role-update',
            'rbac-role-list-role-permission',
            'rbac-role-store-role-permission',
            'rbac-permission-index',
            'rbac-permission-list',
            'rbac-permission-store',
            'rbac-permission-show',
            'rbac-permission-update',
        ];

        // Create Permission objects and store them
        $permissionObjects = [];
        foreach ($permissions as $permission) {
            $permissionObjects[] = Permission::create([
                'permission_name' => $permission,
                'permission_description' => 'Permission for ' . str_replace('-', ' ', $permission), // Convert dash to space for description
            ]);
        }

        // Assign Permissions to Roles
        $developerRole->permissions()->attach(collect($permissionObjects)->pluck('id_permission')); // Attach all permissions to developer
        $editorRole->permissions()->attach([
            Permission::where('permission_name', 'master-jabatan-guru-index')->value('id_permission'),
            Permission::where('permission_name', 'master-jabatan-guru-list')->value('id_permission'),
            Permission::where('permission_name', 'master-guru-index')->value('id_permission'),
            Permission::where('permission_name', 'master-guru-list')->value('id_permission'),
        ]);

        $userRole->permissions()->attach([
            Permission::where('permission_name', 'master-guru-index')->value('id_permission'),
            Permission::where('permission_name', 'master-guru-list')->value('id_permission'),
        ]);

        // Example User Creation and Role Assignment
        // Developer User
        $developerUser = User::create([
            'name' => 'developer',
            'email' => 'developer@gmail.com',
            'password' => Hash::make('developer'),
        ]);
        $developerUser->roles()->attach($developerRole->id_role); // Assign developer role to user

        // Editor User
        $editorUser = User::create([
            'name' => 'editor',
            'email' => 'editor@gmail.com',
            'password' => Hash::make('editor'),
        ]);
        $editorUser->roles()->attach($editorRole->id_role); // Assign editor role to user

        // Normal User
        $normalUser = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('user'),
        ]);
        $normalUser->roles()->attach($userRole->id_role); // Assign user role to user
    }
}
