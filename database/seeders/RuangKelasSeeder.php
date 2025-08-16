<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\RuangKelas;
use App\Models\TahunPelajaran;
use App\Models\Tingkat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuangKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tp = TahunPelajaran::where('status', 'active')->first();
        $tingkat = Tingkat::where('jenjang', 'SMA')->where('nama_tingkat', '10')->first();
        $jurusan = Jurusan::where('nama_jurusan', 'IPA')->first();

        RuangKelas::create([
            'tahun_pelajaran_id' => $tp->id_tahun_pelajaran,
            'tingkat_id' => $tingkat->id_tingkat,
            'jurusan_id' => $jurusan->id_jurusan,
            'nama_ruang_kelas' => '10A',
            'wali_kelas_id' => null,
        ]);
    }
}
