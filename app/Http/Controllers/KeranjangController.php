<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use App\Models\Parkir;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $minDate = now()->addDay()->toDateString();
        $maxDate = now()->addDays(8)->toDateString();
        $validated = $request->validate([
            'armada_id' => 'required|exists:armadas,id',
            'sopir_id' => 'required|exists:sopirs,id',
            'tanggal_mulai' => 'required|date|after_or_equal:' . $minDate . '|before_or_equal:' . $maxDate,
            'estimasi_hari' => 'required|integer|min:1',
            'tempat_jemput' => 'required|string',
            'tempat_antar' => 'required|string',
            'barang_muatan' => 'required|string',
            'bobot' => 'required|integer|min:1',
            'latitude_penjemputan' => 'required|numeric',
            'longitude_penjemputan' => 'required|numeric',
            'latitude_antar' => 'required|numeric',
            'longitude_antar' => 'required|numeric',
            'parkir_latitude' => 'required|numeric',
            'parkir_longitude' => 'required|numeric',
            'harga_sewa' => 'required|numeric',
            'total_jarak' => 'required|numeric'
        ]);

        $armada = Armada::findOrFail($validated['armada_id']);
        if ($validated['bobot'] > $armada->kapasitas) {
            return redirect()->back()
                ->with('error', 'Bobot muatan (' . $validated['bobot'] . ' Ton) melebihi kapasitas armada ' . $armada->no_polisi . ' (' . $armada->kapasitas . ' Ton).')
                ->withInput();
        }

        // Tambahkan client_id = 2 otomatis
        $validated['client_id'] = 2;
        $validated['status'] = 'pending';

        $keranjang = Keranjang::create($validated);

        $keranjang->rute()->create([
            'tempat_jemput' => $validated['tempat_jemput'],
            'tempat_antar' => $validated['tempat_antar'],
            'latitude_penjemputan' => $validated['latitude_penjemputan'],
            'longitude_penjemputan' => $validated['longitude_penjemputan'],
            'latitude_antar' => $validated['latitude_antar'],
            'longitude_antar' => $validated['longitude_antar'],
            'parkir_latitude' => $validated['parkir_latitude'],
            'parkir_longitude' => $validated['parkir_longitude'],
            'total_jarak' => $validated['total_jarak'],
        ]);

        $keranjang->penugasan()->create([
            'sopir_id' => $validated['sopir_id'],
        ]);

        return redirect()->route('keranjang.index')
                        ->with('success', 'Pesanan berhasil ditambahkan ke keranjang!');
    }
    public function update(Request $request, $id)
    {
        try {
            $keranjang = Keranjang::findOrFail($id);
            $penyewaan = $keranjang->penyewaan;

            // Cek apakah penyewaan masih menunggu pembayaran
            if ($penyewaan->status !== 'menunggu_pembayaran') {
                return redirect()->back()->with('error', 'Tidak dapat mengubah item dari pesanan yang sudah diproses!');
            }

            $minDate = now()->addDay()->toDateString();
            $maxDate = now()->addDays(8)->toDateString();
            $validated = $request->validate([
                'armada_id' => 'required|exists:armadas,id',
                'tanggal_mulai' => 'required|date|after_or_equal:' . $minDate . '|before_or_equal:' . $maxDate,
                'estimasi_hari' => 'required|integer|min:1',
                'tempat_jemput' => 'required|string',
                'tempat_antar' => 'required|string',
                'barang_muatan' => 'required|string',
                'bobot' => 'required|integer|min:1',
                'latitude_penjemputan' => 'required|numeric',
                'longitude_penjemputan' => 'required|numeric',
                'latitude_antar' => 'required|numeric',
                'longitude_antar' => 'required|numeric',
                'parkir_latitude' => 'required|numeric',
                'parkir_longitude' => 'required|numeric',
                'harga_sewa' => 'required|numeric',
                'total_jarak' => 'required|numeric',
                'harga_tawar' => 'nullable|numeric|min:0'
            ]);

            $armada = Armada::findOrFail($validated['armada_id']);
            if ($validated['bobot'] > $armada->kapasitas) {
                return redirect()->back()
                    ->with('error', 'Bobot muatan (' . $validated['bobot'] . ' Ton) melebihi kapasitas armada ' . $armada->no_polisi . ' (' . $armada->kapasitas . ' Ton).')
                    ->withInput();
            }

            // Validasi harga tawar jika ada
            if (isset($validated['harga_tawar']) && $validated['harga_tawar'] > 0) {
                $hargaAsli = $validated['harga_sewa'];
                $minHarga = $hargaAsli * 0.9;
                
                if ($validated['harga_tawar'] < $minHarga) {
                    return redirect()->back()
                        ->with('error', 'Harga tawar tidak boleh kurang dari 90% harga asli.')
                        ->withInput();
                }
                
                $validated['harga_sewa'] = $validated['harga_tawar'];
            }

            // Jika armada berubah
            if ($keranjang->armada_id != $validated['armada_id']) {
                // Kembalikan armada lama menjadi tersedia
                $oldArmada = Armada::find($keranjang->armada_id);
                if ($oldArmada) {
                    $oldArmada->update(['status' => 'tersedia']);
                }

                // Set armada baru menjadi tidak tersedia
                $newArmada = Armada::findOrFail($validated['armada_id']);
                $newArmada->update(['status' => 'tidak_tersedia']);
                
                // Update sopir_id juga
                $validated['sopir_id'] = $newArmada->sopir_id;
            }

            // Update keranjang
            $keranjang->update($validated);

            // Update atau buat data rute perjalanan di tabel rute_keranjangs
            $keranjang->rute()->updateOrCreate([], [
                'tempat_jemput' => $validated['tempat_jemput'],
                'tempat_antar' => $validated['tempat_antar'],
                'latitude_penjemputan' => $validated['latitude_penjemputan'],
                'longitude_penjemputan' => $validated['longitude_penjemputan'],
                'latitude_antar' => $validated['latitude_antar'],
                'longitude_antar' => $validated['longitude_antar'],
                'parkir_latitude' => $validated['parkir_latitude'],
                'parkir_longitude' => $validated['parkir_longitude'],
                'total_jarak' => $validated['total_jarak'],
            ]);

            // Update atau buat data penugasan sopir di tabel penugasan_sopirs
            $keranjang->penugasan()->updateOrCreate([], [
                'sopir_id' => $validated['sopir_id'] ?? $keranjang->sopir_id,
            ]);

            // Update total harga penyewaan
            $totalHarga = $penyewaan->keranjangs()->sum('harga_sewa');
            $penyewaan->update(['harga_total' => $totalHarga]);

            return redirect()->route('penyewaan.keranjang', $penyewaan->id)
                             ->with('success', 'Item berhasil diperbarui!');
            
        } catch (\Exception $e) {
            Log::error('Error update keranjang: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $keranjang = Keranjang::findOrFail($id);
            $penyewaan = $keranjang->penyewaan;
            
            // Cek apakah penyewaan masih menunggu pembayaran
            if ($penyewaan->status !== 'menunggu_pembayaran') {
                return redirect()->back()->with('error', 'Tidak dapat menghapus item dari pesanan yang sudah diproses!');
            }            // Kembalikan status armada ke tersedia
            $armada = Armada::find($keranjang->armada_id);
            if ($armada) {
                $armada->update(['status' => 'tersedia']);
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

            // Simpan ke tabel pembatalan_sewas untuk normalisasi
            $keranjang->pembatalan()->updateOrCreate([], [
                'alasan_batal' => $request->alasan_batal
            ]);

            // Kirim notifikasi ke admin
            $penyewaan = $keranjang->penyewaan;
            $clientNama = $penyewaan && $penyewaan->client ? $penyewaan->client->nama : 'Client';
            $kodeTransaksi = $penyewaan ? $penyewaan->kode_transaksi : '-';

            \App\Services\NotifikasiService::kirimKeAdmin(
                "Pengajuan Pembatalan Baru",
                "Ada pengajuan pembatalan baru untuk pesanan #{$kodeTransaksi} dari client {$clientNama}.",
                route('penyewaanAdmin.pembatalan'),
                $keranjang->penyewaan_id
            );

            return back()->with('success', 'Pengajuan pembatalan berhasil dikirim. Menunggu konfirmasi admin.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
