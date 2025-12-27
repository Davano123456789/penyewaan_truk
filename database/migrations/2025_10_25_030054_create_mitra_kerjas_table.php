<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('mitra_kerjas', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama perusahaan, misalnya "UNILEVER"
            $table->string('logo')->nullable(); // Path atau nama file logo
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_kerjas');
    }
};
