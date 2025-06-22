<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            RbacSeeder::class,
            JabatanGuruSeeder::class,
            GuruSeeder::class,
            TahunPelajaranSeeder::class,
            SemesterSeeder::class,
            IuranSeeder::class,
            KeringananSeeder::class,
            SekolahSeeder::class,
            TingkatSeeder::class,
            JurusanSeeder::class,
            RuangKelasSeeder::class,
            SiswaSeeder::class,
            KelasSiswaSeeder::class,
            PpdbApplicationSeeder::class,
        ]);
    }
}
