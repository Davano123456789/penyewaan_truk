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
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->dropColumn('kode_penugasan');
            $table->string('kode_keranjang')->nullable()->after('penyewaan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->dropColumn('kode_keranjang');
            $table->string('kode_penugasan')->nullable()->after('penyewaan_id');
        });
    }
};
