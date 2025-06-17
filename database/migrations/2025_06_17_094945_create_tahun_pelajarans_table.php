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
        Schema::create('tahun_pelajaran', function (Blueprint $table) {
            $table->smallIncrements('id_tahun_pelajaran');
            $table->string('nama_tahun_pelajaran', 20); // ex: 2025/2026
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
        Schema::dropIfExists('tahun_pelajaran');
    }
};
