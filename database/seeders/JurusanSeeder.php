<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_jurusan' => 'IPA', 'jenjang' => 'SMA'],
            ['nama_jurusan' => 'IPS', 'jenjang' => 'SMA'],
            ['nama_jurusan' => 'TKJ', 'jenjang' => 'SMK'],
            ['nama_jurusan' => 'RPL', 'jenjang' => 'SMK'],
        ];

        foreach ($data as $item) {
            Jurusan::firstOrCreate($item);
        }
    }
}
