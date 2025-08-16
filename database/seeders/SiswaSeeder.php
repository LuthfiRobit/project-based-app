<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $i) {
            DB::table('siswa')->insert([
                'user_id' => null,
                'nis' => str_pad($i, 4, '0', STR_PAD_LEFT),
                'nisn' => $faker->unique()->numerify('############'),
                'nama_siswa' => $faker->name,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->date('Y-m-d', '2012-12-31'),
                'agama' => 'Islam',
                'alamat' => $faker->address,
                'no_telepon' => $faker->phoneNumber,
                'nama_ayah' => $faker->name('male'),
                'nama_ibu' => $faker->name('female'),
                'nama_wali' => null,
                'status' => 'active',
                'tanggal_diterima' => now()->subYears(rand(1, 3)),
                'tanggal_lulus' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
