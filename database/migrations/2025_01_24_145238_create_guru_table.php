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
        Schema::create('guru', function (Blueprint $table) {
            $table->increments('id_guru'); // Primary key dengan tipe INT (auto-increment)
            $table->unsignedSmallInteger('jabatan_id'); // Tidak nullable
            $table->foreign('jabatan_id')->references('id_jabatan')->on('jabatan_guru')->onDelete('cascade'); // Relasi ke tabel jabatan
            $table->unsignedInteger('user_id')->nullable(); // Use unsignedInteger here to match id_user
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('set null');
            $table->string('nama_guru');
            $table->string('nip', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon', 15)->nullable();
            $table->string('email')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->string('status_guru')->nullable(); // Status sekarang berupa string (misalnya "PNS", "Honorer", dll.)
            $table->date('tanggal_masuk')->nullable();
            $table->enum('status_pernikahan', ['Lajang', 'Menikah'])->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

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
        Schema::dropIfExists('guru');
    }
};
