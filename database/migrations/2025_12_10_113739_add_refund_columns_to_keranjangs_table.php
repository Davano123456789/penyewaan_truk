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
            $table->string('bukti_refund')->nullable()->after('alasan_batal');
            $table->decimal('nominal_refund', 15, 2)->nullable()->after('bukti_refund');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->dropColumn(['bukti_refund', 'nominal_refund']);
        });
    }
};
