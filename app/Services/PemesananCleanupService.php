<?php

namespace App\Services;

use App\Models\Penyewaan;
use Illuminate\Support\Facades\Log;

class PemesananCleanupService
{
    /**
     * Membersihkan pemesanan dengan status 'menunggu_pembayaran' yang sudah kadaluwarsa (> 10 menit).
     * Serta mengembalikan armada terkait menjadi 'tersedia'.
     */
    public static function cleanExpiredBookings()
    {
        try {
            // Batas waktu kadaluwarsa (10 menit yang lalu)
            $expiredTime = now()->subMinutes(10);

            // Ambil pemesanan yang kadaluwarsa beserta keranjang dan armadanya
            $expiredPenyewaans = Penyewaan::where('status', 'menunggu_pembayaran')
                ->where('created_at', '<', $expiredTime)
                ->with('keranjangs.armada')
                ->get();

            if ($expiredPenyewaans->isEmpty()) {
                return 0;
            }

            $count = 0;
            foreach ($expiredPenyewaans as $penyewaan) {
                // Kembalikan status armada untuk setiap item keranjang
                foreach ($penyewaan->keranjangs as $keranjang) {
                    if ($keranjang->armada) {
                        $keranjang->armada->update(['status' => 'tersedia']);
                    }
                }

                // Hapus penyewaan. 
                // Karena foreign key menggunakan onDelete('cascade'), 
                // tabel keranjangs, rute_keranjangs, dan penugasan_sopirs terkait akan otomatis terhapus.
                $penyewaan->delete();
                $count++;
            }

            Log::info("PemesananCleanupService: Berhasil menghapus {$count} pemesanan kadaluwarsa.");
            return $count;

        } catch (\Exception $e) {
            Log::error("PemesananCleanupService Error: " . $e->getMessage());
            return 0;
        }
    }
}
