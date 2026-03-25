<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use App\Services\NotifikasiService;
use Illuminate\Support\Facades\Auth;

class PenugasanAdminController extends Controller
{
    /**
     * Tampilkan daftar penugasan yang menunggu validasi
     */
    public function index()
    {
        $penugasans = Keranjang::with(['penyewaan', 'armada', 'sopir'])
            ->where('status', 'menunggu_konfirmasi_selesai')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dashboard.penugasanAdmin.index', compact('penugasans'));
    }

    /**
     * Validasi penugasan selesai
     */
    public function validasi($id)
    {
        $keranjang = Keranjang::with('penyewaan')->findOrFail($id);

        // Update status keranjang menjadi SELESAI
        $keranjang->update(['status' => 'selesai']);

        // Kirim Notifikasi ke Client
        NotifikasiService::kirim(
            $keranjang->penyewaan->client_id,
            "Item Pesanan Selesai",
            "Kode Keranjang #" . $keranjang->kode_keranjang . " dari pesanan " . $keranjang->penyewaan->kode_transaksi . " telah divalidasi dan dinyatakan selesai.",
            route('penyewaan.keranjang', $keranjang->penyewaan_id),
            $keranjang->penyewaan_id
        );

        // Kirim Notifikasi ke Sopir
        NotifikasiService::kirim(
            $keranjang->sopir_id,
            "Bukti Selesai Disetujui",
            "Bukti penyelesaian untuk Kode Keranjang #" . $keranjang->kode_keranjang . " telah disetujui oleh admin.",
            route('penugasan.index'),
            $keranjang->penyewaan_id
        );

        // Cek apakah semua item di penyewaan ini sudah selesai
        $penyewaan = $keranjang->penyewaan;
        $allSelesai = Keranjang::where('penyewaan_id', $penyewaan->id)
            ->where('status', '!=', 'selesai')
            ->count();

        if ($allSelesai === 0) {
            $penyewaan->update(['status' => 'selesai']);
            
            // Notifikasi ke Client
            NotifikasiService::kirim(
                $penyewaan->client_id,
                "Pesanan Selesai",
                "Semua penugasan untuk pesanan #" . $penyewaan->kode_transaksi . " telah selesai divalidasi. Terima kasih telah menggunakan jasa kami!",
                route('penyewaan.keranjang', $penyewaan->id),
                $penyewaan->id
            );
        }

        return back()->with('success', 'Penugasan berhasil divalidasi sebagai Selesai!');
    }

    /**
     * Tolak bukti penugasan
     */
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:255'
        ]);

        $keranjang = Keranjang::with('penyewaan')->findOrFail($id);

        // Kembalikan status menjadi AKTIF agar sopir bisa upload ulang
        $keranjang->update(['status' => 'aktif']);

        // Kirim notifikasi ke Sopir bahwa buktinya ditolak
        NotifikasiService::kirim(
            $keranjang->sopir_id,
            "Bukti Penugasan Ditolak",
            "Bukti selesaian untuk pesanan #" . $keranjang->penyewaan->kode_transaksi . " ditolak oleh admin. Alasan: " . $request->alasan . ". Silakan upload ulang.",
            route('penugasan.index'),
            $keranjang->penyewaan_id
        );

        return back()->with('warning', 'Bukti penugasan ditolak. Sopir diminta untuk upload ulang.');
    }
}
