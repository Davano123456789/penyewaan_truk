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
        // Jalankan drop foreign key di block terpisah agar jika gagal tidak merusak jalannya dropping kolom
        try {
            Schema::table('keranjangs', function (Blueprint $table) {
                $table->dropForeign(['sopir_id']);
            });
        } catch (\Exception $e) {
            // Abaikan jika tidak ada foreign key
        }

        Schema::table('keranjangs', function (Blueprint $table) {
            $columnsToDrop = [
                'alasan_batal',
                'nominal_refund',
                'bukti_refund',
                'catatan_admin_batal',
                'catatan', // alasan penolakan/pembatalan admin
                'tempat_jemput',
                'tempat_antar',
                'total_jarak',
                'latitude_jemput',
                'longitude_jemput',
                'latitude_penjemputan',
                'longitude_penjemputan',
                'latitude_antar',
                'longitude_antar',
                'latitude_parkir',
                'longitude_parkir',
                'parkir_latitude',
                'parkir_longitude',
                'bukti_selesai',
                'sopir_id',
                'catatan_penugasan'
            ];

            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('keranjangs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // down logic is omitted as these columns have been fully normalized out
    }
};
