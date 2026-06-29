<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ambil semua data keranjang lama
        $keranjangs = DB::table('keranjangs')->get();

        foreach ($keranjangs as $k) {
            DB::table('penugasan_sopirs')->updateOrInsert(
                ['keranjang_id' => $k->id],
                [
                    'sopir_id' => $k->sopir_id,
                    'catatan_penugasan' => $k->catatan_penugasan ?? null,
                    'bukti_selesai' => $k->bukti_selesai ?? null,
                    'created_at' => $k->created_at,
                    'updated_at' => $k->updated_at,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu menghapus data di down
    }
};
