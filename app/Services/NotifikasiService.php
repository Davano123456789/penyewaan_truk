<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;
use App\Models\Peran;

class NotifikasiService
{
    /**
     * Kirim notifikasi ke user tertentu
     */
    public static function kirim($userId, $judul, $pesan, $url = '#', $penyewaanId = null)
    {
        return Notifikasi::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'url' => $url,
            'penyewaan_id' => $penyewaanId,
            'is_read' => false
        ]);
    }

    /**
     * Kirim notifikasi ke semua admin
     */
    public static function kirimKeAdmin($judul, $pesan, $url = '#', $penyewaanId = null)
    {
        $adminPeran = Peran::where('nama', 'admin')->first();
        if (!$adminPeran) return;

        $admins = User::where('peran_id', $adminPeran->id)->get();

        foreach ($admins as $admin) {
            self::kirim($admin->id, $judul, $pesan, $url, $penyewaanId);
        }
    }
}
