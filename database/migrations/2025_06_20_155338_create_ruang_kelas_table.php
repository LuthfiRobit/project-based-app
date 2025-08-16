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
        Schema::create('ruang_kelas', function (Blueprint $table) {
            $table->smallIncrements('id_ruang_kelas'); // PERBAIKAN: sebelumnya salah

            $table->unsignedSmallInteger('tahun_pelajaran_id')->index();
            $table->foreign('tahun_pelajaran_id')->references('id_tahun_pelajaran')->on('tahun_pelajaran')->onDelete('cascade');

            $table->unsignedSmallInteger('tingkat_id')->index();
            $table->foreign('tingkat_id')->references('id_tingkat')->on('tingkat')->onDelete('cascade');

            $table->unsignedSmallInteger('jurusan_id')->nullable()->index();
            $table->foreign('jurusan_id')->references('id_jurusan')->on('jurusan')->onDelete('set null');

            $table->string('nama_ruang_kelas', 20)->index(); // contoh: "7A"

            $table->unsignedInteger('wali_kelas_id')->nullable()->index();
            $table->foreign('wali_kelas_id')->references('id_guru')->on('guru')->onDelete('set null');

            $table->enum('status', ['active', 'inactive'])->default('inactive');
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
        Schema::dropIfExists('ruang_kelas');
    }
};
