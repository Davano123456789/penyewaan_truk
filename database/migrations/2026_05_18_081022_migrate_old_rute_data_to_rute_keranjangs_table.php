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
        $keranjangs = DB::table('keranjangs')->get();
        foreach ($keranjangs as $keranjang) {
            $exists = DB::table('rute_keranjangs')->where('keranjang_id', $keranjang->id)->exists();
            if (!$exists) {
                DB::table('rute_keranjangs')->insert([
                    'keranjang_id' => $keranjang->id,
                    'tempat_jemput' => $keranjang->tempat_jemput,
                    'tempat_antar' => $keranjang->tempat_antar,
                    'latitude_penjemputan' => $keranjang->latitude_penjemputan,
                    'longitude_penjemputan' => $keranjang->longitude_penjemputan,
                    'latitude_antar' => $keranjang->latitude_antar,
                    'longitude_antar' => $keranjang->longitude_antar,
                    'parkir_latitude' => $keranjang->parkir_latitude,
                    'parkir_longitude' => $keranjang->parkir_longitude,
                    'total_jarak' => $keranjang->total_jarak,
                    'created_at' => $keranjang->created_at ?? now(),
                    'updated_at' => $keranjang->updated_at ?? now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Opsional: Hapus record yang telah dimigrasi jika di-rollback
        DB::table('rute_keranjangs')->truncate();
    }
};
