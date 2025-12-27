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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penyewaan_id');
            $table->string('metode', 50);
            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('bukti_transfer')->nullable();
            $table->timestamps();

            // Foreign key ke tabel penyewaans
            $table->foreign('penyewaan_id')->references('id')->on('penyewaans')->onDelete('cascade');
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
