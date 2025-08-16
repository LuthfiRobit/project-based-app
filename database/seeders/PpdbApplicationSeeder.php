<?php

namespace Database\Seeders;

use App\Models\Tingkat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PpdbApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $grade = Tingkat::where('jenjang', 'SMP')->where('nama_tingkat', '7')->first();

        $gradeId = $grade ? $grade->id_tingkat : null;

        if ($gradeId) {
            foreach (range(1, 10) as $i) {
                DB::table('ppdb_application')->insert([
                    'nama_pendaftar' => $faker->name,
                    'nomor_registrasi' => "PPDB-2025-" . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'tanggal_registrasi' => now()->subDays(rand(0, 30)),
                    'status' => $faker->randomElement(['pending', 'accepted', 'rejected']),
                    'tingkat_terpilih_id' => $gradeId,
                    'transfer_siswa_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
