<?php

namespace Database\Seeders;

use App\Models\Tingkat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TingkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_tingkat' => '1', 'jenjang' => 'SD'],
            ['nama_tingkat' => '2', 'jenjang' => 'SD'],
            ['nama_tingkat' => '3', 'jenjang' => 'SD'],
            ['nama_tingkat' => '7', 'jenjang' => 'SMP'],
            ['nama_tingkat' => '8', 'jenjang' => 'SMP'],
            ['nama_tingkat' => '10', 'jenjang' => 'SMA'],
            ['nama_tingkat' => '11', 'jenjang' => 'SMA'],
            ['nama_tingkat' => '12', 'jenjang' => 'SMA'],
        ];

        foreach ($data as $item) {
            Tingkat::firstOrCreate($item);
        }
    }
}
