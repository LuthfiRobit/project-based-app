<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
                'nama_jabatan' => 'Kepala Sekolah',
                'deskripsi' => 'Pimpinan tertinggi di lingkungan sekolah, bertanggung jawab terhadap seluruh kegiatan sekolah.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Wakil Kepala Sekolah Kurikulum',
                'deskripsi' => 'Membantu kepala sekolah dalam bidang pengelolaan kurikulum dan kegiatan pembelajaran.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Wakil Kepala Sekolah Kesiswaan',
                'deskripsi' => 'Membantu kepala sekolah dalam membina dan menangani kegiatan kesiswaan.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Wakil Kepala Sekolah Sarpras',
                'deskripsi' => 'Bertanggung jawab atas pengelolaan sarana dan prasarana sekolah.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Wakil Kepala Sekolah Humas',
                'deskripsi' => 'Mengelola hubungan sekolah dengan masyarakat dan instansi luar.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Guru Mata Pelajaran',
                'deskripsi' => 'Mengampu mata pelajaran tertentu sesuai kompetensi dan kurikulum.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Guru Kelas',
                'deskripsi' => 'Mengajar dan membina siswa dalam satu kelas (umumnya untuk SD).',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Guru BK (Bimbingan Konseling)',
                'deskripsi' => 'Membimbing siswa secara individu maupun kelompok untuk pengembangan pribadi dan sosial.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Guru Piket',
                'deskripsi' => 'Bertugas mengawasi ketertiban dan kedisiplinan selama hari sekolah berlangsung.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Pembina OSIS',
                'deskripsi' => 'Membimbing dan mendampingi pengurus OSIS dalam menjalankan program kerja mereka.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Wali Kelas',
                'deskripsi' => 'Mengelola administrasi dan pembinaan terhadap siswa dalam satu kelas.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Guru Pendidik Matematika',
                'deskripsi' => 'Mengajar mata pelajaran matematika di sekolah.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Guru Pendidik Bahasa Indonesia',
                'deskripsi' => 'Mengajar mata pelajaran bahasa Indonesia di sekolah.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Guru Pendidik IPA',
                'deskripsi' => 'Mengajar mata pelajaran Ilmu Pengetahuan Alam di sekolah.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Guru Pendidik Sejarah',
                'deskripsi' => 'Mengajar mata pelajaran sejarah di sekolah.',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_jabatan' => 'Guru Pendidik Seni Budaya',
                'deskripsi' => 'Mengajar mata pelajaran seni budaya di sekolah.',
                'status' => 'inactive',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
