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
        Schema::create('ppdb_application', function (Blueprint $table) {
            $table->increments('id_ppdb_application');

            $table->string('nama_pendaftar')->index();
            $table->string('nomor_registrasi')->unique();
            $table->date('tanggal_registrasi')->nullable();

            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending')->index();

            $table->unsignedSmallInteger('tingkat_terpilih_id')->index();
            $table->foreign('tingkat_terpilih_id')->references('id_tingkat')->on('tingkat')->onDelete('cascade');

            $table->unsignedInteger('transfer_siswa_id')->nullable()->index();
            $table->foreign('transfer_siswa_id')->references('id_siswa')->on('siswa')->onDelete('set null');

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
        Schema::dropIfExists('ppdb_application');
    }
};
