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
        Schema::create('kondisi_mobil', function (Blueprint $table) {
            $table->id('id_kondisi');
            $table->unsignedBigInteger('id_mobil');
            $table->text('catatan_defect')->nullable();
            $table->date('tanggal_masuk_bengkel')->nullable();
            $table->date('tanggal_keluar_bengkel')->nullable();
            $table->text('klaim_warranty')->nullable();
            $table->timestamps();

            // Relasi ke tabel mobil
            $table->foreign('id_mobil')->references('id_mobil')->on('mobil')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kondisi_mobil');
    }
};
