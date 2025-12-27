<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sync keranjang status dengan penyewaan yang aktif
        // Untuk semua keranjang yang penyewaannya aktif tapi keranjangnya masih pending
        DB::statement("
            UPDATE keranjangs k
            INNER JOIN penyewaans p ON k.penyewaan_id = p.id
            SET k.status = 'aktif'
            WHERE p.status = 'aktif' AND k.status = 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada yang perlu di-revert karena ini hanya data sync
    }
};
