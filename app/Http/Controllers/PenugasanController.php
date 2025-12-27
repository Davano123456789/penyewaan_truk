<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PenugasanController extends Controller
{
        public function index()
    {
        // Ambil user yang login (harus sopir dengan peran_id = 3)
        $sopirId = Auth::id();
        // Ambil semua keranjang/penugasan yang sopir_id sesuai user login
        // Status bisa aktif atau selesai (tampilkan semua penugasan, baik yang sedang berjalan maupun yang sudah selesai)
        $penugasans = Keranjang::with(['penyewaan', 'armada'])
            ->where('sopir_id', $sopirId)
            ->whereHas('penyewaan', function($query) {
                $query->whereIn('status', ['aktif', 'selesai']);
            })
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        return view('dashboard.sopir.penugasan.index', compact('penugasans'));
    }
        public function show($id)
    {
        $sopirId = Auth::id();

        $penugasan = Keranjang::with(['penyewaan', 'armada'])
            ->where('id', $id)
            ->where('sopir_id', $sopirId)
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
                ->where('sopir_id', $sopirId)
                ->whereHas('penyewaan', function($query) {
                    $query->whereIn('status', ['aktif', 'selesai']);
                })
                ->first();

            if (!$penugasan) {
                return redirect()->route('penugasan.index')
                    ->with('error', 'Penugasan tidak ditemukan!');
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

                    // Update bukti selesai dan status keranjang
                    $penugasan->update([
                        'bukti_selesai' => $uploadedFileUrl,
                        'status' => 'selesai'
                    ]);

                    // Check if semua keranjang untuk penyewaan ini sudah selesai
                    $penyewaan = $penugasan->penyewaan;
                    $allKeranjangsSelesai = \App\Models\Keranjang::where('penyewaan_id', $penyewaan->id)
                        ->where('status', '!=', 'selesai')
                        ->count();

                    // Jika semua keranjang selesai (count = 0), update status penyewaan jadi selesai
                    if ($allKeranjangsSelesai === 0) {
                        $penyewaan->update(['status' => 'selesai']);
                        $message = 'Bukti selesai berhasil diupload! Semua penugasan selesai, penyewaan ditandai selesai.';
                    } else {
                        $message = 'Bukti selesai berhasil diupload! Masih ada penugasan lain yang belum selesai.';
                    }

                    return redirect()->route('penugasan.index')
                        ->with('success', $message);

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

}
