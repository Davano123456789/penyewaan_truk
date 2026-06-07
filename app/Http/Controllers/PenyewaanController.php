<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Penyewaan;
use App\Models\Pembayaran;
use App\Services\NotifikasiService;
use App\Services\PemesananCleanupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Barryvdh\DomPDF\Facade\Pdf;

class PenyewaanController extends Controller
{
    // Halaman Daftar Penyewaan
    public function index()
    {
        // Bersihkan pemesanan kadaluwarsa (> 10 menit) sebelum memuat daftar
        PemesananCleanupService::cleanExpiredBookings();

        // ✅ UBAH INI - Ambil dari user yang login
        $clientId = Auth::id(); // Atau auth()->id()
        
        $penyewaans = Penyewaan::where('client_id', $clientId)
            ->withCount('keranjangs')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard.penyewaan.index', compact('penyewaans'));
    }
    // Detail Penyewaan (Legacy/Redirect)
    public function show($id)
    {
        return redirect()->route('penyewaan.keranjang', $id);
    }
    
    // Halaman Keranjang (Daftar Item dalam Penyewaan)
    public function keranjang($id)
    {
        // Bersihkan pemesanan kadaluwarsa (> 10 menit) sebelum memuat detail keranjang
        PemesananCleanupService::cleanExpiredBookings();

        $penyewaan = Penyewaan::with('keranjangs.armada.sopir', 'keranjangs.sopir', 'keranjangs.rute', 'keranjangs.penugasan')
            ->find($id);

        if (!$penyewaan) {
            return redirect()->route('penyewaan.index')
                ->with('error', 'Penyewaan tidak ditemukan atau sudah kedaluwarsa!');
        }
        
        return view('dashboard.penyewaan.keranjang', compact('penyewaan'));
    }
    
    // Hapus Penyewaan (Jika masih pending)
    public function destroy($id)
    {
        try {
            $penyewaan = Penyewaan::findOrFail($id);
            
            // Cek apakah masih pending atau menunggu pembayaran
            if (!in_array($penyewaan->status, ['pending', 'menunggu_pembayaran'])) {
                return redirect()->back()->with('error', 'Hanya pesanan dengan status pending atau menunggu pembayaran yang dapat dihapus!');
            }

            // Kembalikan status semua armada terkait menjadi tersedia
            foreach ($penyewaan->keranjangs as $item) {
                if ($item->armada) {
                    $item->armada->update(['status' => 'tersedia']);
                }
            }
            
            // Hapus semua keranjang terkait
            $penyewaan->keranjangs()->delete();
            
            // Hapus penyewaan
            $penyewaan->delete();
            
            return redirect()->route('penyewaan.index')->with('success', 'Pesanan berhasil dihapus!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
        public function showPembayaran($penyewaanId)
    {
        // Bersihkan pemesanan kadaluwarsa (> 10 menit) sebelum memuat halaman pembayaran
        PemesananCleanupService::cleanExpiredBookings();

        $penyewaan = Penyewaan::with(['keranjangs.armada', 'keranjangs.rute', 'keranjangs.penugasan', 'pembayaran'])
            ->where('id', $penyewaanId)
            ->where('client_id', Auth::id())
            ->first();

        if (!$penyewaan) {
            return redirect()->route('penyewaan.index')
                ->with('error', 'Penyewaan tidak ditemukan atau sudah kedaluwarsa!');
        }

        // Check if penyewaan is in a valid payment state
        // Either menunggu_pembayaran (initial payment) OR aktif + talangan + menunggu_pelunasan (remaining payment)
        $isValidForPayment = false;

        if ($penyewaan->status == 'menunggu_pembayaran') {
            $isValidForPayment = true;
        } elseif (in_array($penyewaan->status, ['aktif', 'selesai']) && $penyewaan->pembayaran && 
                  $penyewaan->pembayaran->jenis == 'talangan' && 
                  in_array($penyewaan->pembayaran->status, ['menunggu_pelunasan', 'ditolak'])) {
            $isValidForPayment = true;
        }

        if (!$isValidForPayment) {
            return redirect()->route('penyewaan.index')
                ->with('error', 'Penyewaan tidak dapat diubah lagi atau sudah lunas!');
        }

        return view('dashboard.pembayaran.index', compact('penyewaan'));
    }
     public function storePembayaran(Request $request, $penyewaanId)
    {
        try {
            $validated = $request->validate([
                'jenis' => 'required|in:tunai,talangan',
                'metode' => 'required|in:transfer_bca,transfer_bri',
                'tanggal_bayar' => 'required|date',
                'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'jenis.required' => 'Jenis pembayaran wajib dipilih',
                'metode.required' => 'Metode pembayaran wajib dipilih',
                'tanggal_bayar.required' => 'Tanggal bayar wajib diisi',
                'bukti_transfer.required' => 'Bukti transfer wajib diupload',
                'bukti_transfer.image' => 'File harus berupa gambar',
                'bukti_transfer.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
                'bukti_transfer.max' => 'Ukuran gambar maksimal 2MB',
            ]);

            // Cek apakah ini pembayaran pertama atau pembayaran sisa
            $penyewaan = Penyewaan::with('pembayaran')->findOrFail($penyewaanId);
            
            // Cek apakah user adalah pemilik penyewaan
            if ($penyewaan->client_id != Auth::id()) {
                return redirect()->route('penyewaan.index')
                    ->with('error', 'Akses ditolak!');
            }

            // PEMBAYARAN PERTAMA (status menunggu_pembayaran)
            if ($penyewaan->status == 'menunggu_pembayaran') {
                // Hitung jumlah bayar berdasarkan jenis pembayaran
                $jumlahBayar = ($validated['jenis'] === 'talangan') 
                    ? $penyewaan->harga_total_aktif / 2 
                    : $penyewaan->harga_total_aktif;

                // Tentukan status pembayaran (Sekarang selalu menunggu konfirmasi admin)
                $statusPembayaran = 'menunggu_konfirmasi';

                $newStatus = 'menunggu_konfirmasi_pembayaran';
                $successMessage = 'Bukti transfer berhasil diupload! Menunggu konfirmasi admin.';
            }
            // PEMBAYARAN SISA (status aktif/selesai dan talangan menunggu pelunasan ATAU ditolak)
            elseif (in_array($penyewaan->status, ['aktif', 'selesai']) && $penyewaan->pembayaran && 
                    $penyewaan->pembayaran->jenis == 'talangan' && 
                    in_array($penyewaan->pembayaran->status, ['menunggu_pelunasan', 'ditolak'])) {
                
                // Pembayaran sisa = 50% dari total
                $jumlahBayar = $penyewaan->harga_total_aktif / 2;
                $statusPembayaran = 'menunggu_konfirmasi_pelunasan'; // Tetap menunggu konfirmasi pelunasan dari admin
                $newStatus = $penyewaan->status; // Tetap mempertahankan status penyewaan (bisa aktif atau selesai)
                $successMessage = 'Pembayaran sisa berhasil diupload! Menunggu konfirmasi pelunasan dari admin.';
            }
            else {
                return redirect()->route('penyewaan.index')
                    ->with('error', 'Penyewaan tidak dapat diproses untuk pembayaran!');
            }

            // Upload bukti transfer ke Cloudinary
            if ($request->hasFile('bukti_transfer')) {
                try {
                    $uploadedFileUrl = Cloudinary::upload(
                        $request->file('bukti_transfer')->getRealPath(),
                        [
                            'folder' => 'bukti_transfer',
                            'transformation' => [
                                'width' => 1000,
                                'height' => 1000,
                                'crop' => 'limit'
                            ]
                        ]
                    )->getSecurePath();

                    // Simpan atau update data pembayaran
                    if ($penyewaan->status == 'menunggu_pembayaran') {
                        // Pembayaran pertama - buat record baru
                        Pembayaran::create([
                            'penyewaan_id' => $penyewaan->id,
                            'jenis' => $validated['jenis'],
                            'metode' => $validated['metode'],
                            'jumlah_bayar' => $jumlahBayar,
                            'tanggal_bayar' => $validated['tanggal_bayar'],
                            'bukti_transfer' => $uploadedFileUrl,
                            'status' => $statusPembayaran,
                        ]);
                    } else {
                        // Pembayaran sisa - update record pembayaran sebelumnya
                        // Jika status sebelumnya ditolak, jumlah_bayar sudah ditambahkan sisa pelunasan pada upload sebelumnya, jadi tidak perlu ditambah lagi.
                        $newJumlahBayar = ($penyewaan->pembayaran->status == 'ditolak')
                            ? $penyewaan->pembayaran->jumlah_bayar
                            : ($penyewaan->pembayaran->jumlah_bayar + $jumlahBayar);

                        $penyewaan->pembayaran->update([
                            'metode' => $validated['metode'],
                            'jumlah_bayar' => $newJumlahBayar,
                            'tanggal_bayar' => $validated['tanggal_bayar'],
                            'bukti_transfer' => $uploadedFileUrl,
                            'status' => $statusPembayaran // jangan langsung lunas, tunggu konfirmasi admin
                        ]);
                    }

                    // Sync keranjang status dengan penyewaan (hanya jika pembayaran pertama)
                    if ($penyewaan->status == 'menunggu_pembayaran') {
                        $keranjangsNewStatus = ($newStatus === 'aktif') ? 'aktif' : 'pending';
                        \App\Models\Keranjang::where('penyewaan_id', $penyewaan->id)
                            ->update(['status' => $keranjangsNewStatus]);
                    }

                    // Update status penyewaan
                    $penyewaan->update(['status' => $newStatus]);

                    // Kirim Notifikasi ke Admin
                    $pesanNotif = ($penyewaan->status == 'menunggu_konfirmasi_pembayaran') 
                        ? "Ada pembayaran baru dari " . Auth::user()->nama . " untuk pesanan #" . $penyewaan->kode_transaksi
                        : "Ada upload pelunasan dari " . Auth::user()->nama . " untuk pesanan #" . $penyewaan->kode_transaksi;
                    
                    NotifikasiService::kirimKeAdmin(
                        "Konfirmasi Pembayaran Diperlukan",
                        $pesanNotif,
                        route('penyewaanAdmin.show', $penyewaan->id),
                        $penyewaan->id
                    );

                    return redirect()->route('penyewaan.index')
                        ->with('success', $successMessage);

                } catch (\Exception $e) {
                    \Log::error('Error upload to Cloudinary: ' . $e->getMessage());
                    return back()->with('error', 'Gagal mengupload bukti transfer: ' . $e->getMessage())
                        ->withInput();
                }
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error upload bukti transfer: ' . $e->getMessage());
            return redirect()->route('penyewaan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayatPembayaran()
    {
        $pembayarans = Pembayaran::whereHas('penyewaan', function($q) {
            $q->where('client_id', Auth::id());
        })->with('penyewaan')->orderBy('created_at', 'desc')->get();

        return view('dashboard.pembayaran.riwayat', compact('pembayarans'));
    }

    public function detailPembayaran($id)
    {
        $pembayaran = Pembayaran::with(['penyewaan.keranjangs.armada', 'penyewaan.keranjangs.rute', 'penyewaan.keranjangs.penugasan', 'penyewaan.client'])
            ->find($id);

        if (!$pembayaran) {
            return redirect()->route('penyewaan.riwayatPembayaran')
                ->with('error', 'Pembayaran tidak ditemukan!');
        }

        // Security check
        if ($pembayaran->penyewaan->client_id != Auth::id()) {
            abort(403, 'Akses ditolak!');
        }

        return view('dashboard.pembayaran.detail', compact('pembayaran'));
    }

    public function cetakInvoice($id)
    {
        $penyewaan = Penyewaan::with(['client', 'keranjangs.armada', 'keranjangs.rute', 'keranjangs.penugasan', 'pembayaran'])
            ->where('client_id', Auth::id())
            ->find($id);

        if (!$penyewaan) {
            return redirect()->route('penyewaan.index')
                ->with('error', 'Invoice tidak ditemukan atau sudah kedaluwarsa!');
        }

        $pdf = Pdf::loadView('dashboard.penyewaanAdmin.invoice', compact('penyewaan'));
        
        return $pdf->download('invoice-' . $penyewaan->kode_transaksi . '.pdf');
    }
}