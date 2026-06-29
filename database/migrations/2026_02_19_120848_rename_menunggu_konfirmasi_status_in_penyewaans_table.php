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
        Schema::table('penyewaans', function (Blueprint $table) {
            // Update data lama
            DB::table('penyewaans')
                ->where('status', 'menunggu_konfirmasi')
                ->update(['status' => 'menunggu_konfirmasi_pembayaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyewaans', function (Blueprint $table) {
            DB::table('penyewaans')
                ->where('status', 'menunggu_konfirmasi_pembayaran')
                ->update(['status' => 'menunggu_konfirmasi']);
        });
    }
};
