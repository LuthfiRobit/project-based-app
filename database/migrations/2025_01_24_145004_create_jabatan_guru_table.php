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
        Schema::create('jabatan_guru', function (Blueprint $table) {
            $table->smallIncrements('id_jabatan'); // Primary key dengan tipe SMALLINT
            $table->string('nama_jabatan');
            $table->text('deskripsi')->nullable();
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
        Schema::dropIfExists('jabatan_guru');
    }
};
