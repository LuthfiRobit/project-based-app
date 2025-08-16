<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sekolah')->insert([
            [
                'npsn' => '10101010',
                'nama_sekolah' => 'SD Negeri 01 Jakarta',
                'jenjang' => 'SD',
                'alamat' => 'Jl. Merdeka No.1',
                'desa_kelurahan' => 'Gambir',
                'kecamatan' => 'Gambir',
                'kabupaten_kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '10110',
                'no_telp' => '0211234567',
                'email' => 'sdn01jakarta@example.com',
                'website' => 'https://sdn01jakarta.sch.id',
                'kepala_sekolah' => 'Budi Santoso',
                'nip_kepala_sekolah' => '197001011990011001',
                'logo' => 'logo/sdn01jakarta.png',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
