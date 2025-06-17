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
        // Buat role developer jika belum ada
        $developerRole = Role::firstOrCreate(
            ['role_name' => 'developer'],
            ['role_description' => 'Developer full access']
        );

        // Ambil semua permission aktif
        $activePermissions = Permission::where('is_active', true)->pluck('id_permission');

        // Attach semua permission aktif ke role developer
        $developerRole->permissions()->syncWithoutDetaching($activePermissions);

        // Buat user developer jika belum ada
        $developerUser = User::firstOrCreate(
            ['email' => 'developer@gmail.com'],
            [
                'name' => 'Developer',
                'username' => 'developer',
                'password' => Hash::make('developer'),
            ]
        );

        // Assign role developer ke user
        $developerUser->roles()->syncWithoutDetaching([$developerRole->id_role]);

        $this->command->info('✅ Developer role and user seeded with full permission access.');
    }
}
