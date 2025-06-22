<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->smallIncrements('id_sekolah'); // PK

            // Identitas umum
            $table->string('npsn', 20)->unique()->index(); // Nomor Pokok Sekolah Nasional
            $table->string('nama_sekolah');
            $table->enum('jenjang', ['SD', 'MI', 'SMP', 'MTS', 'SMA', 'SMK', 'MA'])->index();

            // Alamat
            $table->string('alamat')->nullable();
            $table->string('desa_kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten_kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos', 10)->nullable();

            // Kontak
            $table->string('no_telp', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // Kepala Sekolah
            $table->string('kepala_sekolah')->nullable();
            $table->string('nip_kepala_sekolah', 30)->nullable();

            // Logo
            $table->string('logo')->nullable(); // path file/logo sekolah

            // Meta data
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
};
