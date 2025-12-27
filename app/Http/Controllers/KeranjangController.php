<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use App\Models\Parkir;
use App\Models\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
        public function create()
    {
        // Ambil semua parkir dengan koordinat
        $parkirs = Parkir::whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->get();
        
        return view('pemesanan.create', compact('parkirs'));
    }

    public function getArmadaTersedia(Request $request)
    {
        $lat = $request->lat;
        $lng = $request->lng;

        // Hitung jarak dari setiap parkir ke lokasi jemput
        // Menggunakan formula Haversine
        $parkirs = Parkir::select('parkirs.*')
            ->selectRaw("
                (6371 * acos(
                    cos(radians(?)) * 
                    cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(latitude))
                )) AS distance
            ", [$lat, $lng, $lat])
            ->with(['armadas' => function($query) {
                $query->where('status', 'aktif')
                      ->with('sopir');
            }])
            ->having('distance', '<', 100) // Maksimal 100km radius
            ->orderBy('distance', 'asc')
            ->get();

        $armadas = [];
        foreach ($parkirs as $parkir) {
            foreach ($parkir->armadas as $armada) {
                $armadas[] = [
                    'id' => $armada->id,
                    'no_polisi' => $armada->no_polisi,
                    'merek' => $armada->merek,
                    'jenis' => $armada->jenis,
                    'kapasitas' => $armada->kapasitas,
                    'sopir_id' => $armada->sopir_id,
                    'sopir' => $armada->sopir->nama ?? 'Belum Ada',
                    'parkir_nama' => $parkir->nama,
                    'parkir_lat' => $parkir->latitude,
                    'parkir_lng' => $parkir->longitude,
                    'jarak_ke_jemput' => round($parkir->distance, 2)
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $armadas
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'armada_id' => 'required|exists:armadas,id',
            'sopir_id' => 'required|exists:sopirs,id',
            'tanggal_mulai' => 'required|date',
            'estimasi_hari' => 'required|integer|min:1',
            'tempat_jemput' => 'required|string',
            'tempat_antar' => 'required|string',
            'barang_muatan' => 'required|string',
            'latitude_penjemputan' => 'required|numeric',
            'longitude_penjemputan' => 'required|numeric',
            'latitude_antar' => 'required|numeric',
            'longitude_antar' => 'required|numeric',
            'parkir_latitude' => 'required|numeric',
            'parkir_longitude' => 'required|numeric',
            'harga_sewa' => 'required|numeric',
            'total_jarak' => 'required|numeric'
        ]);

        // Tambahkan client_id = 2 otomatis
        $validated['client_id'] = 2;
        $validated['status'] = 'pending';

        $keranjang = Keranjang::create($validated);

        return redirect()->route('keranjang.index')
                        ->with('success', 'Pesanan berhasil ditambahkan ke keranjang!');
    }
    public function destroy($id)
    {
        try {
            $keranjang = Keranjang::findOrFail($id);
            $penyewaan = $keranjang->penyewaan;
            
            // Cek apakah penyewaan masih pending atau menunggu_pembayaran
            if (!in_array($penyewaan->status, ['pending', 'menunggu_pembayaran'])) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus item dari pesanan yang sudah diproses!');
            }            // ✅ TAMBAHKAN INI - Kembalikan status armada ke aktif
            $armada = Armada::find($keranjang->armada_id);
            if ($armada) {
                $armada->update(['status' => 'aktif']); // Atau 'tersedia', sesuai dengan status di database Anda
            }
            
            // Hapus item
            $keranjang->delete();
            
            // Update total harga
            $totalHarga = $penyewaan->keranjangs()->sum('harga_sewa');
            
            if ($totalHarga > 0) {
                $penyewaan->update(['harga_total' => $totalHarga]);
                return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang!');
            } else {
                // Jika keranjang kosong, hapus penyewaan juga
                $penyewaan->delete();
                return redirect()->route('penyewaan.index')->with('success', 'Keranjang kosong, pesanan dihapus!');
            }
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function ajukanBatal(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string|max:255',
        ]);

        try {
            $keranjang = Keranjang::findOrFail($id);
            
            // Pastikan status keranjang aktif sebelum diajukan batal
            if ($keranjang->status !== 'aktif') {
                return back()->with('error', 'Hanya item dengan status aktif yang dapat diajukan pembatalan.');
            }

            $keranjang->update([
                'status' => 'menunggu_konfirmasi_batal',
                'alasan_batal' => $request->alasan_batal
            ]);

            return back()->with('success', 'Pengajuan pembatalan berhasil dikirim. Menunggu konfirmasi admin.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
