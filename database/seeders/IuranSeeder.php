<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('iuran')->insert([
            [
                'semester_id' => 1,
                'nama_iuran' => 'SPP Bulanan',
                'nominal_iuran' => 150000,
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester_id' => 1,
                'nama_iuran' => 'Uang Kegiatan',
                'nominal_iuran' => 50000,
                'status' => 'inactive',
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
