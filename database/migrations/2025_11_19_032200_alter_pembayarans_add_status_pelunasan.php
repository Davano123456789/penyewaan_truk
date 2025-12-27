<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw statement to safely modify enum values without requiring doctrine/dbal
        DB::statement("ALTER TABLE `pembayarans` MODIFY `status` ENUM('lunas','menunggu_pelunasan','menunggu_konfirmasi_pelunasan') NOT NULL DEFAULT 'lunas';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `pembayarans` MODIFY `status` ENUM('lunas','menunggu_pelunasan') NOT NULL DEFAULT 'lunas';");
    }
};
