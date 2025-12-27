<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PenugasanNotification;

class PenyewaanAdminController extends Controller
{
     public function index(Request $request)
    {
        $query = Penyewaan::with(['client', 'keranjangs.armada', 'pembayaran'])
            ->withCount('keranjangs')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        $penyewaans = $query->paginate(15);

        return view('dashboard.penyewaanAdmin.index', compact('penyewaans'));
    }

    /**
     * Tampilkan detail penyewaan
     */
    public function show($id)
    {
        $penyewaan = Penyewaan::with(['client', 'keranjangs.armada', 'pembayaran'])
            ->findOrFail($id);

        return view('dashboard.penyewaanAdmin.show', compact('penyewaan'));
    }

    /**
     * Konfirmasi pembayaran (pembayaran pertama atau pelunasan)
     */
    public function konfirmasiPembayaran($id)
    {
        try {
            DB::beginTransaction();

            $penyewaan = Penyewaan::with('pembayaran')->findOrFail($id);

            // SKENARIO 1: Konfirmasi Pembayaran Pertama (status menunggu_konfirmasi)
            if ($penyewaan->status == 'menunggu_konfirmasi') {
                
                if (!$penyewaan->pembayaran) {
                    return back()->with('error', 'Belum ada bukti pembayaran yang diupload!');
                }

                // Update status penyewaan menjadi AKTIF
                $penyewaan->update(['status' => 'aktif']);

                // Update semua keranjang terkait menjadi AKTIF
                \App\Models\Keranjang::where('penyewaan_id', $penyewaan->id)
                    ->update(['status' => 'aktif']);

                // Kirim email pemberitahuan penugasan ke setiap sopir yang ada di keranjang
                try {
                    \Log::info('=== START Pengiriman Email Penugasan ===');
                    
                    $keranjangs = \App\Models\Keranjang::with(['sopir', 'armada'])
                        ->where('penyewaan_id', $penyewaan->id)
                        ->get();

                    \Log::info('Total keranjang untuk penyewaan_id=' . $penyewaan->id . ': ' . $keranjangs->count());
                    
                    $groupedBySopir = $keranjangs->groupBy('sopir_id');
                    \Log::info('Jumlah grup sopir: ' . $groupedBySopir->count());

                    foreach ($groupedBySopir as $sopirId => $items) {
                        \Log::info('Proses sopir_id=' . $sopirId . ', items=' . $items->count());

                        if (empty($sopirId)) {
                            \Log::warning('Ada keranjang tanpa sopir_id pada penyewaan: ' . $penyewaan->id . '. Items count: ' . $items->count());
                            continue;
                        }

                        $first = $items->first();
                        $sopir = $first->sopir;

                        if (!$sopir) {
                            \Log::warning('Sopir tidak ditemukan untuk sopir_id=' . $sopirId . ' pada penyewaan ' . $penyewaan->id);
                            continue;
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
                                $pesanWA = "Halo *" . ($sopir->nama ?? $sopir->name) . "*,\n\n";
                                $pesanWA .= "Anda mendapat *TUGAS BARU* dari penyewaan #{$penyewaan->id}.\n";
                                $pesanWA .= "--------------------------------\n";
                                
                                foreach ($items as $index => $item) {
                                    $num = $items->count() > 1 ? ($index + 1) . ". " : "";
                                    $pesanWA .= "{$num}*Tanggal*: " . date('d-m-Y', strtotime($item->tanggal_mulai)) . "\n";
                                    $pesanWA .= "   *Rute*: {$item->tempat_jemput} -> {$item->tempat_antar}\n";
                                    $pesanWA .= "   *Muatan*: {$item->barang_muatan}\n";
                                    $pesanWA .= "   *Armada*: {$item->armada->no_polisi} ({$item->armada->merek})\n\n";
                                }
                                
                                $pesanWA .= "--------------------------------\n";
                                $pesanWA .= "Silakan cek email atau dashboard sopir untuk detail lengkapnya.\n\n";
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
                return back()->with('success', 'Pembayaran berhasil dikonfirmasi! Penyewaan sekarang aktif. Sopir telah diberi tahu melalui email jika tersedia.');
            }

            // SKENARIO 2: Konfirmasi Pelunasan (status aktif + pembayaran menunggu_konfirmasi_pelunasan)
            elseif ($penyewaan->status == 'aktif' && $penyewaan->pembayaran && 
                    $penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan') {
                
                // Update status pembayaran menjadi LUNAS
                $penyewaan->pembayaran->update(['status' => 'lunas']);

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
        try {
            DB::beginTransaction();

            $penyewaan = Penyewaan::with('pembayaran')->findOrFail($id);

            // Cegah jika sudah selesai
            if ($penyewaan->status == 'selesai') {
                return back()->with('error', 'Tidak dapat menolak, penyewaan sudah selesai.');
            }

            // Jika ini adalah penolakan untuk pelunasan (pembayaran sisa)
            if ($penyewaan->status == 'aktif' && $penyewaan->pembayaran && $penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan') {
                // Kembalikan status pembayaran menjadi menunggu_pelunasan dan hapus bukti_transfer agar customer upload ulang
                $p = $penyewaan->pembayaran;
                // hapus file bukti jika tersimpan di public
                if ($p->bukti_transfer) {
                    $filePath = public_path($p->bukti_transfer);
                    if ($filePath && file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
                $p->update([
                    'status' => 'menunggu_pelunasan',
                    'bukti_transfer' => null,
                    'tanggal_bayar' => null
                ]);

                // Pastikan penyewaan tetap aktif dan keranjang tetap aktif
                $penyewaan->update(['status' => 'aktif']);
                \App\Models\Keranjang::where('penyewaan_id', $penyewaan->id)
                    ->update(['status' => 'aktif']);

            } else {
                // Hapus bukti pembayaran dan record pembayaran jika ada (pembayaran pertama)
                if ($penyewaan->pembayaran) {
                    $filePath = public_path($penyewaan->pembayaran->bukti_transfer);
                    if ($filePath && file_exists($filePath)) {
                        @unlink($filePath);
                    }
                    $penyewaan->pembayaran->delete();
                }

                // Update status penyewaan kembali ke menunggu_pembayaran
                $penyewaan->update([
                    'status' => 'menunggu_pembayaran',
                    'catatan_admin' => null
                ]);

                // Sync keranjang status kembali ke pending
                \App\Models\Keranjang::where('penyewaan_id', $penyewaan->id)
                    ->update(['status' => 'pending']);
            }

            DB::commit();

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

            // Hapus semua keranjang terkait
            $penyewaan->keranjangs()->delete();

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
        $keranjangs = \App\Models\Keranjang::with(['penyewaan.client', 'armada', 'sopir'])
            ->whereIn('status', ['menunggu_konfirmasi_batal', 'dibatalkan'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('dashboard.penyewaanAdmin.pembatalan', compact('keranjangs'));
    }

    public function prosesPembatalan(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'nominal_refund' => 'nullable|numeric',
            'bukti_refund' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();
            
            $keranjang = \App\Models\Keranjang::with('armada')->findOrFail($id);

            if ($request->action === 'approve') {
                $updateData = ['status' => 'dibatalkan'];

                // Handle Refund
                if ($request->hasFile('bukti_refund')) {
                    $file = $request->file('bukti_refund');
                    $upload = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload($file->getRealPath(), [
                        'folder' => 'bukti_refund'
                    ]);
                    
                    $updateData['bukti_refund'] = $upload->getSecurePath();
                    $updateData['nominal_refund'] = $request->nominal_refund;
                }

                // Setujui pembatalan
                $keranjang->update($updateData);
                
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

                $message = 'Pembatalan disetujui. Status keranjang dibatalkan, armada kembali tersedia, dan total harga diperbarui.';
            } else {
                // Tolak pembatalan, kembalikan ke aktif
                $keranjang->update([
                    'status' => 'aktif',
                    'alasan_batal' => null // Reset alasan batal jika ditolak (opsional, atau biarkan history)
                ]);
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
