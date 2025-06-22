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
        Schema::create('siswa', function (Blueprint $table) {
            $table->increments('id_siswa');

            $table->unsignedInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('set null');

            $table->string('nis')->nullable()->index();
            $table->string('nisn')->nullable()->index();
            $table->string('nama_siswa')->index();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('agama')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 15)->nullable();

            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_wali')->nullable();

            $table->enum('status', ['ppdb', 'active', 'graduated', 'left', 'rejected'])->default('ppdb')->index();

            $table->date('tanggal_diterima')->nullable();
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
        Schema::dropIfExists('siswa');
    }
};
