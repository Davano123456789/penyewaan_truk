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

            // Validasi koordinat GPS Sopir
            $sopirLat = $request->input('sopir_lat');
            $sopirLng = $request->input('sopir_lng');
            if (empty($sopirLat) || empty($sopirLng)) {
                return back()->with('error', 'Gagal memverifikasi lokasi. Anda wajib menyalakan GPS dan mengizinkan browser mengakses lokasi Anda untuk mengunggah bukti.');
            }

            // Hitung jarak ke Pool Parkir armada terkait
            $parkir = $penugasan->armada->parkir ?? null;
            if ($parkir && $parkir->latitude && $parkir->longitude) {
                $lat1 = floatval($sopirLat);
                $lng1 = floatval($sopirLng);
                $lat2 = floatval($parkir->latitude);
                $lng2 = floatval($parkir->longitude);

                // Haversine formula (jarak lurus bola bumi dalam meter)
                $earthRadius = 6371000;
                $dLat = deg2rad($lat2 - $lat1);
                $dLng = deg2rad($lng2 - $lng1);
                $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
                $c = 2 * atan2(sqrt($a), sqrt(1-$a));
                $distance = $earthRadius * $c;

                // Batas toleransi jarak (misal 300 meter untuk GPS mobile yang kurang akurat)
                $maxDistance = 300;

                if ($distance > $maxDistance) {
                    return back()->with('error', 'Unggah bukti ditolak. Anda terdeteksi berada ' . round($distance) . ' meter di luar area pool parkir (' . $parkir->nama . '). Sesuai SOP, Anda harus berada di area pool parkir terlebih dahulu untuk mengembalikan armada.');
                }
            }

            // Validasi tanggal mulai sewa
            if ($penugasan->tanggal_mulai) {
                $today = \Carbon\Carbon::today();
                $startDate = \Carbon\Carbon::parse($penugasan->tanggal_mulai)->startOfDay();
                if ($today->lt($startDate)) {
                    return redirect()->route('penugasan.index')
                        ->with('error', 'Anda tidak dapat mengunggah bukti selesai sebelum tanggal mulai sewa (' . \Carbon\Carbon::parse($penugasan->tanggal_mulai)->format('d-m-Y') . ').');
                }
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
