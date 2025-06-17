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
        Schema::create('semester', function (Blueprint $table) {
            $table->smallIncrements('id_semester');

            $table->unsignedSmallInteger('tahun_pelajaran_id');
            $table->foreign('tahun_pelajaran_id')->references('id_tahun_pelajaran')->on('tahun_pelajaran')->onDelete('cascade'); // Relasi ke tabel jabatan

            $table->enum('nama_semester', ['ganjil', 'genap']);
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
        Schema::dropIfExists('semester');
    }
};
