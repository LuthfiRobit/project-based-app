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
        Schema::create('keringanan', function (Blueprint $table) {
            $table->smallIncrements('id_keringanan'); // Primary Key: smallint

            $table->string('nama_keringanan'); // e.g., "Beasiswa Anak Guru", "Subsidi"
            $table->enum('status', ['active', 'inactive'])->default('inactive');

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->timestamps();

            // Index untuk performa filter
            $table->index('status');
            $table->index('nama_keringanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keringanan');
    }
};
