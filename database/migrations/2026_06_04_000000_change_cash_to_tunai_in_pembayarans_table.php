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
        // 1. Temporarily modify enum to allow both cash, tunai, and talangan
        DB::statement("ALTER TABLE `pembayarans` MODIFY `jenis` ENUM('cash', 'tunai', 'talangan') NOT NULL DEFAULT 'cash';");

        // 2. Update existing records
        DB::table('pembayarans')->where('jenis', 'cash')->update(['jenis' => 'tunai']);

        // 3. Set the final enum structure, removing 'cash' and setting default to 'tunai'
        DB::statement("ALTER TABLE `pembayarans` MODIFY `jenis` ENUM('tunai', 'talangan') NOT NULL DEFAULT 'tunai';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Temporarily modify enum to allow cash, tunai, and talangan
        DB::statement("ALTER TABLE `pembayarans` MODIFY `jenis` ENUM('cash', 'tunai', 'talangan') NOT NULL DEFAULT 'tunai';");

        // 2. Revert records
        DB::table('pembayarans')->where('jenis', 'tunai')->update(['jenis' => 'cash']);

        // 3. Revert to original enum
        DB::statement("ALTER TABLE `pembayarans` MODIFY `jenis` ENUM('cash', 'talangan') NOT NULL DEFAULT 'cash';");
    }
};
