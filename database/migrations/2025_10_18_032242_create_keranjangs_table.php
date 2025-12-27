<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('keranjangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penyewaan_id')->nullable(); // relasi ke penyewaans
            $table->unsignedBigInteger('sopir_id')->nullable(); // relasi ke users (mungkin sopir)
            $table->unsignedBigInteger('armada_id')->nullable(); // relasi ke armadas

            $table->date('tanggal_mulai')->nullable();
            $table->decimal('harga_sewa', 15, 2)->nullable();
            $table->integer('estimasi_hari')->nullable();
            $table->string('tempat_jemput')->nullable();
            $table->string('tempat_antar')->nullable();
            $table->string('barang_muatan')->nullable();
            $table->string('bukti_selesai')->nullable(); // bisa untuk simpan file bukti upload
            $table->string('status')->default('pending');

            $table->decimal('latitude_penjemputan', 10, 7)->nullable();
            $table->decimal('longitude_antar', 10, 7)->nullable();

            $table->timestamps();

            // Foreign key (optional, bisa kamu aktifkan kalau tabel terkait sudah ada)
            $table->foreign('penyewaan_id')->references('id')->on('penyewaans')->onDelete('cascade');
            $table->foreign('sopir_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('armada_id')->references('id')->on('armadas')->onDelete('set null');
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjangs');
    }
};
