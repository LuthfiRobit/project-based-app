<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanGuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menambahkan 5 data jabatan guru
        DB::table('jabatan_guru')->insert([
            [
                'nama_jabatan' => 'Guru Pendidik Matematika',
                'deskripsi' => 'Mengajar mata pelajaran matematika di sekolah.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jabatan' => 'Guru Pendidik Bahasa Indonesia',
                'deskripsi' => 'Mengajar mata pelajaran bahasa Indonesia di sekolah.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jabatan' => 'Guru Pendidik IPA',
                'deskripsi' => 'Mengajar mata pelajaran Ilmu Pengetahuan Alam di sekolah.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jabatan' => 'Guru Pendidik Sejarah',
                'deskripsi' => 'Mengajar mata pelajaran sejarah di sekolah.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jabatan' => 'Guru Pendidik Seni Budaya',
                'deskripsi' => 'Mengajar mata pelajaran seni budaya di sekolah.',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
