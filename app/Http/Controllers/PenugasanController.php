<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penyewaan;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Barryvdh\DomPDF\Facade\Pdf;

class PenugasanController extends Controller
{
        public function index()
    {
        // Ambil user yang login (harus sopir dengan peran_id = 3)
        $sopirId = Auth::id();
        // Ambil semua keranjang/penugasan yang sopir_id sesuai user login
        // Status bisa aktif atau selesai (tampilkan semua penugasan, baik yang sedang berjalan maupun yang sudah selesai)
        $penugasans = Keranjang::with(['penyewaan', 'armada', 'rute', 'penugasan'])
            ->whereHas('penugasan', function($query) use ($sopirId) {
                $query->where('sopir_id', $sopirId);
            })
            ->whereHas('penyewaan', function($query) {
                $query->whereIn('status', ['aktif', 'selesai']);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('dashboard.sopir.penugasan.index', compact('penugasans'));
    }
        public function show($id)
    {
        $sopirId = Auth::id();

        $penugasan = Keranjang::with(['penyewaan', 'armada', 'rute', 'penugasan'])
            ->where('id', $id)
            ->whereHas('penugasan', function($query) use ($sopirId) {
                $query->where('sopir_id', $sopirId);
            })
            ->whereHas('penyewaan', function($query) {
                $query->whereIn('status', ['aktif', 'selesai']);
            })
            ->firstOrFail();

        return view('dashboard.sopir.penugasan.detail', compact('penugasan'));
    }
        public function uploadBukti(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'bukti_selesai' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'bukti_selesai.required' => 'Bukti selesai wajib diupload',
                'bukti_selesai.image' => 'File harus berupa gambar',
                'bukti_selesai.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
                'bukti_selesai.max' => 'Ukuran gambar maksimal 2MB',
            ]);

            $sopirId = Auth::id();

            $penugasan = Keranjang::where('id', $id)
                ->whereHas('penugasan', function($query) use ($sopirId) {
                    $query->where('sopir_id', $sopirId);
                })
                ->whereHas('penyewaan', function($query) {
                    $query->whereIn('status', ['aktif', 'selesai']);
                })
                ->first();

            if (!$penugasan) {
                return redirect()->route('penugasan.index')
                    ->with('error', 'Penugasan tidak ditemukan!');
            }

            if (!in_array($penugasan->status, ['truk_sampai', 'revisi_bukti'])) {
                return redirect()->route('penugasan.index')
                    ->with('error', 'Anda tidak dapat mengunggah bukti penyelesaian sebelum pelanggan mengonfirmasi truk telah sampai di tujuan.');
            }

            // Cegah jika status pembayaran masih menunggu pelunasan
            if ($penugasan->penyewaan && $penugasan->penyewaan->pembayaran && $penugasan->penyewaan->pembayaran->status == 'menunggu_pelunasan') {
                return redirect()->route('penugasan.index')
                    ->with('error', 'Anda tidak dapat mengunggah bukti selesai. Pelanggan belum melunasi sisa tagihan penyewaan.');
            }

            // Upload bukti selesai ke Cloudinary
            if ($request->hasFile('bukti_selesai')) {
                try {
                    $uploadedFileUrl = Cloudinary::upload(
                        $request->file('bukti_selesai')->getRealPath(),
                        [
                            'folder' => 'bukti_selesai',
                            'transformation' => [
                                'width' => 1000,
                                'height' => 1000,
                                'crop' => 'limit'
                            ]
                        ]
                    )->getSecurePath();

                    // Update bukti selesai dan status keranjang menjadi menunggu konfirmasi
                    $penugasan->update([
                        'bukti_selesai' => $uploadedFileUrl,
                        'status' => 'menunggu_konfirmasi_selesai'
                    ]);

                    $penugasan->penugasan()->updateOrCreate([], [
                        'bukti_selesai' => $uploadedFileUrl,
                    ]);

                    // Kirim Notifikasi ke Admin
                    \App\Services\NotifikasiService::kirimKeAdmin(
                        "Validasi Penugasan Diperlukan",
                        "Sopir " . Auth::user()->nama . " telah mengunggah bukti penyelesaian untuk pesanan #" . $penugasan->penyewaan->kode_transaksi . ". Silakan validasi.",
                        route('penugasanAdmin.index'), // Kita akan buat route ini nanti
                        $penugasan->penyewaan_id
                    );

                    return redirect()->route('penugasan.index')
                        ->with('success', 'Bukti selesai berhasil diupload! Menunggu konfirmasi dan validasi dari admin.');

                } catch (\Exception $e) {
                    \Log::error('Error upload to Cloudinary: ' . $e->getMessage());
                    return back()->with('error', 'Gagal mengupload bukti: ' . $e->getMessage());
                }
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator);
        } catch (\Exception $e) {
            \Log::error('Error upload bukti selesai: ' . $e->getMessage());
            return redirect()->route('penugasan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cetakInvoice($id)
    {
        $penugasan = Keranjang::findOrFail($id);
        $penyewaan = Penyewaan::with(['client', 'keranjangs.armada', 'pembayaran'])
            ->findOrFail($penugasan->penyewaan_id);

        $pdf = Pdf::loadView('dashboard.penyewaanAdmin.invoice', compact('penyewaan'));
        
        return $pdf->download('invoice-' . $penyewaan->kode_transaksi . '.pdf');
    }
}
