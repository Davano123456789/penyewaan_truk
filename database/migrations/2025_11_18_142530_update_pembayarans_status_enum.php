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
        // Untuk MySQL, kita perlu drop column dan buat ulang dengan enum yang baru
        Schema::table('pembayarans', function (Blueprint $table) {
            // Ubah enum dengan menambah status baru 'menunggu_konfirmasi_pelunasan'
            $table->enum('status', ['lunas', 'menunggu_pelunasan', 'menunggu_konfirmasi_pelunasan'])
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // Revert ke enum lama
            $table->enum('status', ['lunas', 'menunggu_pelunasan'])
                ->change();
        });
    }
};
