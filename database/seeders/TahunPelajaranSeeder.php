<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tahun_pelajaran')->insert([
            [
                'nama_tahun_pelajaran' => '2024/2025',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tahun_pelajaran' => '2023/2024',
                'status' => 'inactive',
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
