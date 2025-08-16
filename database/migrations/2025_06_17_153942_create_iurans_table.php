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
        Schema::create('iuran', function (Blueprint $table) {
            $table->smallIncrements('id_iuran'); // PRIMARY KEY: smallint auto increment

            $table->unsignedSmallInteger('tahun_pelajaran_id');
            $table->foreign('tahun_pelajaran_id')->references('id_tahun_pelajaran')->on('tahun_pelajaran')->onDelete('cascade'); // Relasi ke tabel jabatan

            $table->string('nama_iuran'); // Nama iuran: ex. SPP, uang kegiatan
            $table->unsignedInteger('nominal_iuran'); // Nominal: gunakan unsigned untuk nilai positif

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->timestamps();

            $table->index('tahun_pelajaran_id');
            $table->index('status');
            $table->index('nama_iuran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran');
    }
};
