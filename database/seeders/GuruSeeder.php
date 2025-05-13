<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menggunakan Faker untuk menghasilkan data acak
        $faker = Faker::create();

        // Mengambil semua jabatan dari tabel jabatan_guru untuk relasi
        $jabatanIds = DB::table('jabatan_guru')->pluck('id_jabatan')->toArray();

        // Mengambil data status guru dan pendidikan terakhir dari config
        $statusGuruList = array_keys(config('static-data.status_guru')); // Ambil hanya kunci untuk status guru
        $pendidikanTerakhirList = array_keys(config('static-data.pendidikan_terakhir')); // Ambil hanya kunci untuk pendidikan terakhir

        // Menambahkan 20 data guru
        foreach (range(1, 20) as $index) {
            DB::table('guru')->insert([
                'jabatan_id' => $faker->randomElement($jabatanIds), // Pilih id jabatan secara acak
                'nama_guru' => $faker->name, // Nama guru acak
                'nip' => $faker->optional()->numerify('###############'), // NIP acak (nullable)
                'alamat' => $faker->address, // Alamat acak (nullable)
                'no_telepon' => $faker->optional()->phoneNumber, // Nomor telepon acak (nullable)
                'email' => $faker->optional()->safeEmail, // Email acak (nullable)
                'tanggal_lahir' => $faker->optional()->date('Y-m-d', '2000-01-01'), // Tanggal lahir acak (nullable)
                'jenis_kelamin' => $faker->randomElement(['L', 'P']), // Jenis kelamin acak
                'pendidikan_terakhir' => $faker->randomElement($pendidikanTerakhirList), // Pendidikan terakhir acak dari config (singkatan)
                'status_guru' => $faker->randomElement($statusGuruList), // Status guru acak dari config (singkatan)
                'tanggal_masuk' => $faker->optional()->date('Y-m-d', '2022-01-01'), // Tanggal masuk acak (nullable)
                'status_pernikahan' => $faker->randomElement(['Lajang', 'Menikah']), // Status pernikahan acak
                'foto' => $faker->optional()->imageUrl(200, 200, 'people', true), // URL foto acak (nullable)
                'status' => $faker->randomElement(['active', 'inactive']), // Status aktif atau tidak
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
