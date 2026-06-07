<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PenugasanNotification;
use Barryvdh\DomPDF\Facade\Pdf;

class PenyewaanAdminController extends Controller
{
     public function index(Request $request)
    {
        $query = Penyewaan::with(['client', 'keranjangs.armada', 'keranjangs.rute', 'keranjangs.penugasan', 'pembayaran'])
            ->withCount('keranjangs')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'menunggu_pelunasan') {
                $query->where('status', 'aktif')
                      ->whereHas('pembayaran', function($q) {
                          $q->where('status', 'menunggu_pelunasan');
                      });
            } elseif ($request->status == 'menunggu_konfirmasi_pelunasan') {
                $query->where('status', 'aktif')
                      ->whereHas('pembayaran', function($q) {
                          $q->where('status', 'menunggu_konfirmasi_pelunasan');
                      });
            } else {
                $query->where('status', $request->status);
            }
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhere('id', 'like', '%' . $search . '%')
                  ->orWhere('kode_transaksi', 'like', '%' . $search . '%');
            });
        }

        $penyewaans = $query->get();

        return view('dashboard.penyewaanAdmin.index', compact('penyewaans'));
    }

    /**
     * Tampilkan detail penyewaan
     */
    public function show($id)
    {
        $penyewaan = Penyewaan::with(['client', 'keranjangs.armada', 'keranjangs.rute', 'keranjangs.penugasan', 'pembayaran'])
            ->findOrFail($id);

        return view('dashboard.penyewaanAdmin.show', compact('penyewaan'));
    }

    public function cetakInvoice($id)
    {
        $penyewaan = Penyewaan::with(['client', 'keranjangs.armada', 'keranjangs.rute', 'pembayaran'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('dashboard.penyewaanAdmin.invoice', compact('penyewaan'));
        
        return $pdf->download('invoice-penyewaan-' . $penyewaan->kode_transaksi . '.pdf');
    }

    /**
     * Konfirmasi pembayaran (pembayaran pertama atau pelunasan)
     */
    public function konfirmasiPembayaran($id)
    {
        try {
            DB::beginTransaction();

            $penyewaan = Penyewaan::with('pembayaran')->findOrFail($id);

            // SKENARIO 1: Konfirmasi Pembayaran Pertama (status menunggu_konfirmasi_pembayaran)
            if ($penyewaan->status == 'menunggu_konfirmasi_pembayaran') {
                
                if (!$penyewaan->pembayaran) {
                    return back()->with('error', 'Belum ada bukti pembayaran yang diupload!');
                }

                // Update status penyewaan menjadi AKTIF
                $penyewaan->update(['status' => 'aktif']);

                // Update status pembayaran sesuai jenisnya
                if ($penyewaan->pembayaran) {
                    $pembayaranStatus = ($penyewaan->pembayaran->jenis == 'tunai') ? 'lunas' : 'menunggu_pelunasan';
                    $penyewaan->pembayaran->update(['status' => $pembayaranStatus]);
                }

                // Update semua keranjang terkait menjadi AKTIF
                \App\Models\Keranjang::where('penyewaan_id', $penyewaan->id)
                    ->update(['status' => 'aktif']);

                // Kirim email pemberitahuan penugasan ke setiap sopir yang ada di keranjang
                try {
                    \Log::info('=== START Pengiriman Email Penugasan ===');
                    
                    $keranjangs = \App\Models\Keranjang::with(['penugasan.sopir', 'armada', 'rute'])
                        ->where('penyewaan_id', $penyewaan->id)
                        ->get();

                    \Log::info('Total keranjang untuk penyewaan_id=' . $penyewaan->id . ': ' . $keranjangs->count());
                    
                    $groupedBySopir = $keranjangs->groupBy(function($item) {
                        return $item->penugasan->sopir_id ?? null;
                    });
                    \Log::info('Jumlah grup sopir: ' . $groupedBySopir->count());

                    foreach ($groupedBySopir as $sopirId => $items) {
                        \Log::info('Proses sopir_id=' . $sopirId . ', items=' . $items->count());

                        if (empty($sopirId)) {
                            \Log::warning('Ada keranjang tanpa sopir_id pada penyewaan: ' . $penyewaan->id . '. Items count: ' . $items->count());
                            continue;
                        }

                        $first = $items->first();
                        $sopir = $first->penugasan->sopir ?? null;

                        if (!$sopir) {
                            \Log::warning('Sopir tidak ditemukan untuk sopir_id=' . $sopirId . ' pada penyewaan ' . $penyewaan->id);
                            continue;
                        }

                        // --- KIRIM NOTIFIKASI DASHBOARD ---
                        try {
                            \App\Services\NotifikasiService::kirim(
                                $sopirId,
                                "Penugasan Baru",
                                "Anda ditugaskan untuk pengiriman pesanan #" . $penyewaan->kode_transaksi . " (" . $items->count() . " item).",
                                route('penugasan.index'),
                                $penyewaan->id
                            );
                        } catch (\Throwable $e) {
                            \Log::error('✗ Gagal mengirim Notifikasi Dashboard ke sopir id ' . $sopirId . ': ' . $e->getMessage());
                        }

                        $email = $sopir->email ?? null;
                        \Log::info('Sopir: ' . ($sopir->nama ?? $sopir->name ?? 'Unknown') . ', Email: ' . ($email ?? 'KOSONG'));

                        if (empty($email)) {
                            \Log::warning('Sopir id=' . $sopirId . ' tidak memiliki email. Lewati pengiriman.');
                            continue;
                        }

                        try {
                            \Log::info('Akan mengirim ke email: ' . $email);
                            
                            $mailable = new PenugasanNotification($sopir, $penyewaan, $items);
                            Mail::to($email)->send($mailable);
                            
                            \Log::info('✓ Email penugasan berhasil terkirim ke: ' . $email);
                        } catch (\Throwable $e) {
                            \Log::error('✗ Gagal mengirim email ke ' . $email . ': ' . $e->getMessage());
                        }

                        // --- KIRIM WHATSAPP VIA FONNTE ---
                        try {
                            // Ambil nomor telepon dari tabel users (asumsi kolom 'telepon' ada)
                            $telepon = $sopir->telepon;

                            if (!empty($telepon)) {
                                \Log::info('Akan mengirim WhatsApp ke: ' . $telepon);

                                // Buat pesan WhatsApp
                                $pesanWA = "Halo " . ($sopir->nama ?? $sopir->name) . ",\n\n";
                                $pesanWA .= "Anda mendapat TUGAS BARU.\n";
                                $pesanWA .= "================================\n";

                                foreach ($items as $index => $item) {
                                    if ($items->count() > 1) {
                                        $pesanWA .= "[ Item " . ($index + 1) . " ]\n";
                                    }
                                    $pesanWA .= "Kode Item : " . $item->kode_keranjang . "\n";
                                    $pesanWA .= "Tanggal   : " . date('d-m-Y', strtotime($item->tanggal_mulai)) . "\n";
                                    $pesanWA .= "Rute      : " . ($item->rute->tempat_jemput ?? '-') . "\n";
                                    $pesanWA .= "            -> " . ($item->rute->tempat_antar ?? '-') . "\n";
                                    $pesanWA .= "Muatan    : " . $item->barang_muatan . "\n";
                                    $pesanWA .= "Armada    : " . $item->armada->no_polisi . " (" . $item->armada->merek . ")\n";
                                    if ($index < $items->count() - 1) {
                                        $pesanWA .= "--------------------------------\n";
                                    }
                                }

                                $pesanWA .= "================================\n";
                                $pesanWA .= "Silakan cek dashboard sopir untuk detail lengkapnya.\n\n";
                                $pesanWA .= "Terima kasih,\nAdmin Penyewaan Truk";

                                // Panggil service (Instantiate on the fly or inject)
                                // Karena kita di loop dan method ini static-ish kalau pake Facade, tapi kita pake class biasa
                                // Lebih aman _new_ saja di sini atau resolve dari container
                                $waService = app(\App\Services\WhatsappService::class);
                                $waService->sendMessage($telepon, $pesanWA);
                            } else {
                                \Log::warning('Sopir id=' . $sopirId . ' tidak memiliki nomor telepon. Skip WA.');
                            }

                        } catch (\Throwable $e) {
                             \Log::error('✗ Gagal mengirim WA ke sopir id ' . $sopirId . ': ' . $e->getMessage());
                        }
                        // ---------------------------------
                    }
                    
                    \Log::info('=== END Pengiriman Email & WA Penugasan ===');
                } catch (\Throwable $e) {
                    // Jika proses grouping/generalisasi gagal, log dan lanjutkan (tidak menggagalkan konfirmasi)
                    \Log::error('Gagal proses notifikasi sopir:');
                    \Log::error('  Message: ' . $e->getMessage());
                    \Log::error('  Trace: ' . $e->getTraceAsString());
                }

                DB::commit();

                // Kirim Notifikasi ke Client
                NotifikasiService::kirim(
                    $penyewaan->client_id,
                    "Pembayaran Dikonfirmasi",
                    "Pembayaran untuk pesanan #" . $penyewaan->kode_transaksi . " telah dikonfirmasi. Status pesanan sekarang AKTIF.",
                    route('penyewaan.keranjang', $penyewaan->id),
                    $penyewaan->id
                );

                return back()->with('success', 'Pembayaran berhasil dikonfirmasi! Penyewaan sekarang aktif. Sopir telah diberi tahu melalui email jika tersedia.');
            }

            // SKENARIO 2: Konfirmasi Pelunasan (status aktif + pembayaran menunggu_konfirmasi_pelunasan)
            elseif ($penyewaan->status == 'aktif' && $penyewaan->pembayaran && 
                    $penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan') {
                
                // Update status pembayaran menjadi LUNAS
                $penyewaan->pembayaran->update(['status' => 'lunas']);

                // Kirim Notifikasi ke Client
                NotifikasiService::kirim(
                    $penyewaan->client_id,
                    "Pelunasan Dikonfirmasi",
                    "Pelunasan untuk pesanan #" . $penyewaan->kode_transaksi . " telah dikonfirmasi. Terima kasih!",
                    route('penyewaan.keranjang', $penyewaan->id),
                    $penyewaan->id
                );

                DB::commit();
                return back()->with('success', 'Pelunasan berhasil dikonfirmasi! Pembayaran sekarang LUNAS.');
            }

            else {
                return back()->with('error', 'Penyewaan tidak dapat dikonfirmasi pada status ini!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error konfirmasi pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengkonfirmasi pembayaran!');
        }
    }

    /**
     * Tolak pembayaran
     */
    public function tolakPembayaran(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string'
        ]);
        try {
            DB::beginTransaction();

            $penyewaan = Penyewaan::with('pembayaran')->findOrFail($id);

            // Cegah jika sudah selesai
            if ($penyewaan->status == 'selesai') {
                return back()->with('error', 'Tidak dapat menolak, penyewaan sudah selesai.');
            }

            // Jika ini adalah penolakan untuk pelunasan (pembayaran sisa)
            if ($penyewaan->status == 'aktif' && $penyewaan->pembayaran && $penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan') {
                // Update status pembayaran menjadi ditolak dan simpan catatan
                $p = \App\Models\Pembayaran::where('penyewaan_id', $penyewaan->id)
                    ->where('status', 'menunggu_konfirmasi_pelunasan')
                    ->first();

                if ($p) {
                    $p->update([
                        'status' => 'ditolak',
                        'catatan' => $request->catatan
                    ]);
                }

                // Pastikan penyewaan tetap aktif dan keranjang tetap aktif
                $penyewaan->update(['status' => 'aktif']);
                \App\Models\Keranjang::where('penyewaan_id', $penyewaan->id)
                    ->update(['status' => 'aktif']);

            } else {
                // Update status pembayaran menjadi ditolak
                if ($penyewaan->pembayaran) {
                    $penyewaan->pembayaran->update([
                        'status' => 'ditolak',
                        'catatan' => $request->catatan
                    ]);
                }

                // Update status penyewaan kembali ke menunggu_pembayaran
                $penyewaan->update([
                    'status' => 'menunggu_pembayaran'
                ]);

                // Sync keranjang status kembali ke pending
                \App\Models\Keranjang::where('penyewaan_id', $penyewaan->id)
                    ->update(['status' => 'pending']);
            }

            DB::commit();

            // Kirim Notifikasi ke Client
            NotifikasiService::kirim(
                $penyewaan->client_id,
                "Pembayaran Ditolak",
                "Maaf, pembayaran untuk pesanan #" . $penyewaan->kode_transaksi . " ditolak oleh admin. Alasan: " . $request->catatan . ". Silakan periksa kembali bukti transfer Anda.",
                route('penyewaan.keranjang', $penyewaan->id),
                $penyewaan->id
            );

            return back()->with('success', 'Pembayaran ditolak. Penyewaan kembali ke status menunggu pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error tolak pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Gagal menolak pembayaran!');
        }
    }

    /**
     * Hapus penyewaan (hanya admin)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $penyewaan = Penyewaan::with(['keranjangs', 'pembayaran'])
                ->findOrFail($id);

            // Hapus bukti pembayaran jika ada
            if ($penyewaan->pembayaran && $penyewaan->pembayaran->bukti_transfer) {
                $filePath = public_path($penyewaan->pembayaran->bukti_transfer);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $penyewaan->pembayaran->delete();
            }

            // Kembalikan status semua armada terkait menjadi tersedia
            foreach ($penyewaan->keranjangs as $item) {
                if ($item->armada) {
                    $item->armada->update(['status' => 'tersedia']);
                }
            }

            // Hapus semua keranjang terkait

            // Hapus penyewaan
            $penyewaan->delete();

            DB::commit();

            return redirect()->route('penyewaanAdmin.index')
                ->with('success', 'Penyewaan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error hapus penyewaan admin: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus penyewaan: ' . $e->getMessage());
        }
    }

    public function indexPembatalan()
    {
        $keranjangs = \App\Models\Keranjang::with(['penyewaan.client', 'armada', 'sopir', 'pembatalan'])
            ->whereHas('penyewaan')
            ->whereIn('status', ['menunggu_konfirmasi_batal', 'dibatalkan'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dashboard.penyewaanAdmin.pembatalan', compact('keranjangs'));
    }

    public function prosesPembatalan(Request $request, $id)
    {
        $rules = [
            'action' => 'required|in:approve,reject',
            'nominal_refund' => 'nullable|numeric',
            'bukti_refund' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'catatan' => 'nullable|string'
        ];

        $messages = [];

        if ($request->action === 'approve' && $request->nominal_refund > 0) {
            $rules['bukti_refund'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
            $messages['bukti_refund.required'] = 'Bukti refund harus diisi';
        }

        if ($request->action === 'reject') {
            $rules['catatan'] = 'required|string';
            $messages['catatan.required'] = 'Catatan harus diisi';
        }

        $request->validate($rules, $messages);

        try {
            DB::beginTransaction();
            
            $keranjang = \App\Models\Keranjang::with('armada', 'penyewaan')->findOrFail($id);

            if ($request->action === 'approve') {
                $updateData = ['status' => 'dibatalkan'];
                $pembatalanData = [];

                // Handle Refund
                if ($request->hasFile('bukti_refund')) {
                    $file = $request->file('bukti_refund');
                    $upload = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload($file->getRealPath(), [
                        'folder' => 'bukti_refund'
                    ]);
                    
                    $securePath = $upload->getSecurePath();
                    $updateData['bukti_refund'] = $securePath;
                    $updateData['nominal_refund'] = $request->nominal_refund;

                    $pembatalanData['bukti_refund'] = $securePath;
                    $pembatalanData['nominal_refund'] = $request->nominal_refund;
                }

                // Setujui pembatalan
                $keranjang->update($updateData);

                // Simpan ke tabel pembatalan_sewas untuk normalisasi
                $keranjang->pembatalan()->updateOrCreate([], $pembatalanData);
                
                // Kembalikan armada menjadi tersedia (aktif)
                if ($keranjang->armada) {
                    $keranjang->armada->update(['status' => 'tersedia']);
                }

                // Recalculate Total Price for Penyewaan
                $penyewaan = $keranjang->penyewaan;
                if ($penyewaan) {
                    $newTotal = $penyewaan->keranjangs()
                        ->where('status', '!=', 'dibatalkan')
                        ->sum('harga_sewa');
                    
                    $penyewaan->update(['harga_total' => $newTotal]);
                }

                // Kirim Notifikasi ke Client
                NotifikasiService::kirim(
                    $penyewaan->client_id,
                    "Pembatalan Disetujui",
                    "Permintaan pembatalan untuk item di pesanan #" . $penyewaan->kode_transaksi . " telah disetujui.",
                    route('penyewaan.keranjang', $penyewaan->id),
                    $penyewaan->id
                );

                $message = 'Pembatalan disetujui. Status keranjang dibatalkan, armada kembali tersedia, dan total harga diperbarui.';
            } else {
                // Tolak pembatalan, kembalikan ke aktif
                $keranjang->update([
                    'status' => 'aktif',
                    'catatan' => $request->catatan
                ]);

                // Simpan ke tabel pembatalan_sewas untuk normalisasi
                $keranjang->pembatalan()->updateOrCreate([], [
                    'catatan' => $request->catatan
                ]);

                // Kirim Notifikasi ke Client
                NotifikasiService::kirim(
                    $keranjang->penyewaan->client_id,
                    "Pembatalan Ditolak",
                    "Permintaan pembatalan ditolak. Alasan: " . $request->catatan,
                    route('penyewaan.keranjang', $keranjang->penyewaan->id),
                    $keranjang->penyewaan->id
                );

                $message = 'Pembatalan ditolak. Status keranjang kembali aktif.';
            }

            DB::commit();
            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
