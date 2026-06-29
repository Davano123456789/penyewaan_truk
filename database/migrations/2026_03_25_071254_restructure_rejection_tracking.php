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
        // 1. Drop catatan from penyewaans
        Schema::table('penyewaans', function (Blueprint $table) {
            $table->dropColumn('catatan');
        });

        // 2. Update pembayarans
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('bukti_transfer');
            $table->enum('status', ['lunas', 'menunggu_pelunasan', 'menunggu_konfirmasi_pelunasan', 'ditolak'])
                ->default('menunggu_konfirmasi_pelunasan')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn('catatan');
            $table->enum('status', ['lunas', 'menunggu_pelunasan', 'menunggu_konfirmasi_pelunasan'])
                ->change();
        });

        Schema::table('penyewaans', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('status');
        });
    }
};
