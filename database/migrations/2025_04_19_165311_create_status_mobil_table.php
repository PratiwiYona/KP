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
        Schema::create('status_mobil', function (Blueprint $table) {
            $table->id('id_status'); // Primary Key
            $table->unsignedBigInteger('id_mobil'); // Foreign Key dari tabel mobil
            $table->string('kode_parkir')->nullable(); // nullable
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_mobil')->references('id_mobil')->on('mobil')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_mobil');
    }
};
