<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = DB::table('ruang_kelas')->pluck('id_ruang_kelas')->toArray();
        $students = DB::table('siswa')->pluck('id_siswa')->toArray();

        foreach ($students as $studentId) {
            $classroomId = $classrooms[array_rand($classrooms)];

            DB::table('kelas_siswa')->insert([
                'siswa_id' => $studentId,
                'ruang_kelas_id' => $classroomId,
                'status' => 'active',
                'tanggal_masuk' => now()->subMonths(3),
                'tanggal_lulus' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
