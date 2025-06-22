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
        Schema::create('kelas_siswa', function (Blueprint $table) {
            $table->increments('id_kelas_siswa');

            $table->unsignedInteger('siswa_id')->index();
            $table->foreign('siswa_id')->references('id_siswa')->on('siswa')->onDelete('cascade');

            $table->unsignedSmallInteger('ruang_kelas_id')->index();
            $table->foreign('ruang_kelas_id')->references('id_ruang_kelas')->on('ruang_kelas')->onDelete('cascade');

            $table->enum('status', ['active', 'promoted', 'graduated', 'left'])->default('active')->index();

            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_lulus')->nullable();

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
        Schema::dropIfExists('kelas_siswa');
    }
};
