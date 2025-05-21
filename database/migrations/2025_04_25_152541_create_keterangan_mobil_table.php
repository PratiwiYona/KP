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
        Schema::create('keterangan_mobil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mobil')->references('id_mobil')->on('mobil')->onDelete('cascade');
            $table->foreignId('id_keterangan')->references('id')->on('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keterangan_mobil');
    }
};
