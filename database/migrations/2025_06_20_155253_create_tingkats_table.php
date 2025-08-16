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
        Schema::create('tingkat', function (Blueprint $table) {
            $table->smallIncrements('id_tingkat');
            $table->string('nama_tingkat', 10)->index(); // contoh: 1, 12, X, XII
            $table->enum('jenjang', ['SD', 'MI', 'SMP', 'MTS', 'SMA', 'SMK', 'MA'])->index();

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
        Schema::dropIfExists('tingkat');
    }
};
