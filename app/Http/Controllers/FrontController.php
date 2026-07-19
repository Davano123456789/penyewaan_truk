<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mitra;
use App\Models\Armada;
use App\Models\Parkir;
use App\Models\Keranjang;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Auth\Events\Registered;

use App\Models\Keunggulan;

class FrontController extends Controller
{
    public function index()
    {
        // Ambil semua data mitra
        $mitras = Mitra::orderBy('created_at', 'desc')->get();

        // Ambil data keunggulan
        $keunggulans = Keunggulan::orderBy('created_at', 'desc')->get();

        // Ambil riwayat penyewaan yang selesai
        $riwayatPenyewaan = Penyewaan::with(['client', 'keranjangs.armada', 'keranjangs.rute', 'keranjangs.penugasan'])
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->take(6)
            ->get();
        
        return view('home', compact('mitras', 'riwayatPenyewaan', 'keunggulans'));
    }
    
    public function pemesanan($id = null)
    {
        // Ambil semua parkir dengan koordinat
        $parkirs = Parkir::whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->get();
        
        // Ambil jenis truk yang tersedia (distinct dari armadas)
        $jenisTruk = Armada::where('status', 'tersedia')
                           ->whereNotNull('jenis')
                           ->distinct()
                           ->pluck('jenis')
                           ->toArray();

        $editItem = null;
        if ($id) {
            $editItem = Keranjang::with(['armada', 'rute'])->findOrFail($id);
            // Tambahkan jenis truk yang sedang diedit jika tidak ada di list tersedia
            if ($editItem->armada && !in_array($editItem->armada->jenis, $jenisTruk)) {
                $jenisTruk[] = $editItem->armada->jenis;
            }
        }
        
        return view('pemesanan', compact('parkirs', 'jenisTruk', 'editItem'));
    }
public function detailArmada($id)
{
    $armada = Armada::with('sopir', 'parkir')->findOrFail($id);
    return view('armadaDetail', compact('armada'));
}

// Di Controller API atau FrontController
public function getArmadaTersedia(Request $request)
{
    $lat = $request->query('lat');
    $lng = $request->query('lng');
    $jenis = $request->query('jenis');
    $currentArmadaId = $request->query('current_armada_id');

    // Ambil semua tempat parkir lalu urutkan berdasarkan jarak rute jalan OSRM.
    $parkirs = Parkir::whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get()
        ->map(function ($parkir) use ($lat, $lng) {
            $parkir->distance = $this->getOsrmDistanceKm(
                $parkir->latitude,
                $parkir->longitude,
                $lat,
                $lng
            );

            return $parkir;
        })
        ->sortBy(function ($parkir) {
            return $parkir->distance ?? INF;
        })
        ->values();

    if ($parkirs->isEmpty()) {
        return response()->json(['data' => [], 'parkir' => null]);
    }

    $chosenParkir = null;
    $armadas = [];
    $skippedParkirs = [];

    foreach ($parkirs as $parkir) {
        // Ambil armada yang AKTIF saja, sesuai jenis, dan di parkir ini
        $query = Armada::where('parkir_id', $parkir->id)
            ->where('jenis', $jenis)
            ->with('sopir');

        // Jika sedang edit, masukkan juga armada yang sedang digunakan di item tersebut
        if ($currentArmadaId) {
            $query->where(function($q) use ($currentArmadaId) {
                $q->where('status', 'tersedia')
                  ->orWhere('id', $currentArmadaId);
            });
        } else {
            $query->where('status', 'tersedia');
        }

        $availableArmadas = $query->get();

        if ($availableArmadas->count() > 0) {
            $chosenParkir = $parkir;
            $armadas = $availableArmadas->map(function($armada) {
                return [
                    'id' => $armada->id,
                    'no_polisi' => $armada->no_polisi,
                    'merek' => $armada->merek,
                    'jenis' => $armada->jenis,
                    'kapasitas' => $armada->kapasitas,
                    'sopir' => $armada->sopir->nama ?? 'Tidak ada sopir',
                    'status' => $armada->status
                ];
            })->toArray();
            break; // Berhenti mencari karena sudah menemukan parkir terdekat yang memiliki armada tersedia
        } else {
            // Parkir terdekat tapi kosong (tidak memiliki jenis truk terpilih yang berstatus tersedia)
            $skippedParkirs[] = [
                'id' => $parkir->id,
                'nama' => $parkir->nama,
                'alamat' => $parkir->alamat,
                'latitude' => $parkir->latitude,
                'longitude' => $parkir->longitude,
                'distance' => $parkir->distance
            ];
        }
    }

    // Fallback: Jika tidak ada parkir yang memiliki armada dari jenis terpilih, gunakan parkir terdekat pertama
    if (!$chosenParkir && $parkirs->isNotEmpty()) {
        $chosenParkir = $parkirs->first();
    }

    // Filter agar chosenParkir tidak masuk ke dalam list skippedParkirs
    if ($chosenParkir) {
        $skippedParkirs = array_values(array_filter($skippedParkirs, function($p) use ($chosenParkir) {
            return $p['id'] !== $chosenParkir->id;
        }));
    }

    return response()->json([
        'data' => $armadas,
        'parkir' => $chosenParkir ? [
            'id' => $chosenParkir->id,
            'nama' => $chosenParkir->nama,
            'alamat' => $chosenParkir->alamat,
            'latitude' => $chosenParkir->latitude,
            'longitude' => $chosenParkir->longitude,
            'distance' => $chosenParkir->distance
        ] : null,
        'skipped_parkirs' => $skippedParkirs
    ]);
}

private function getOsrmDistanceKm($fromLat, $fromLng, $toLat, $toLng): ?float
{
    try {
        $response = Http::timeout(8)->get(
            "https://router.project-osrm.org/route/v1/driving/{$fromLng},{$fromLat};{$toLng},{$toLat}",
            [
                'overview' => 'false',
                'alternatives' => 'false',
                'steps' => 'false',
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        $distanceMeters = data_get($response->json(), 'routes.0.distance');

        return $distanceMeters !== null ? round($distanceMeters / 1000, 2) : null;
    } catch (\Exception $e) {
        \Log::warning('OSRM distance error: ' . $e->getMessage());
        return null;
    }
}

public function storePemesanan(Request $request)
{
    try {
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
        
        // CEK APAKAH ARMADA MASIH AKTIF
        if ($armada->status !== 'tersedia') {
            return redirect()->back()
                ->with('error', 'Armada sudah tidak tersedia. Silakan pilih armada lain.')
                ->withInput();
        }

        // CEK APAKAH BOBOT MELEBIHI KAPASITAS ARMADA
        if ($validated['bobot'] > $armada->kapasitas) {
            return redirect()->back()
                ->with('error', 'Bobot muatan (' . $validated['bobot'] . ' Ton) melebihi kapasitas armada ' . $armada->no_polisi . ' (' . $armada->kapasitas . ' Ton).')
                ->withInput();
        }
        
        // AMBIL CLIENT ID DARI USER YANG SEDANG LOGIN
        $clientId = Auth::id();
        
        // Validasi harga tawar jika ada
        if (isset($validated['harga_tawar']) && $validated['harga_tawar'] > 0) {
            $hargaAsli = $validated['harga_sewa'];
            $minHarga = $hargaAsli * 0.9;
            
            if ($validated['harga_tawar'] < $minHarga) {
                return redirect()->back()
                    ->with('error', 'Harga tawar tidak boleh kurang dari 90% harga asli (Rp ' . number_format($minHarga, 0, ',', '.') . ')')
                    ->withInput();
            }
            
            $validated['harga_sewa'] = $validated['harga_tawar'];
        }
        
        // STEP 1: BUAT/AMBIL PENYEWAAN - STATUS MENUNGGU_PEMBAYARAN
        $penyewaan = Penyewaan::firstOrCreate(
            ['client_id' => $clientId, 'status' => 'menunggu_pembayaran'],
            [
                'harga_total' => 0,
                'kode_transaksi' => 'TRK-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)))
            ]
        );

        // Jika kode_transaksi masih kosong (untuk data lama), generate sekarang
        if (!$penyewaan->kode_transaksi) {
            $penyewaan->update([
                'kode_transaksi' => 'TRK-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)))
            ]);
        }

        // STEP 2: SIMPAN KE KERANJANG
        $itemCount = $penyewaan->keranjangs()->count() + 1;
        $kodeKeranjang = "KRJ-" . str_replace('TRK-', '', $penyewaan->kode_transaksi) . "-" . $itemCount;

        $keranjang = Keranjang::create([
            'penyewaan_id' => $penyewaan->id,
            'kode_keranjang' => $kodeKeranjang,
            'armada_id' => $validated['armada_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'harga_sewa' => $validated['harga_sewa'],
            'estimasi_hari' => $validated['estimasi_hari'],
            'barang_muatan' => $validated['barang_muatan'],
            'bobot' => $validated['bobot'],
            'status' => 'pending'
        ]);

        // Simpan data rute perjalanan ke tabel baru
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

        // Simpan data penugasan sopir ke tabel baru
        $keranjang->penugasan()->create([
            'sopir_id' => $armada->sopir_id,
        ]);

        // STEP 3: UPDATE STATUS ARMADA MENJADI TIDAK TERSEDIA
        $armada->update(['status' => 'tidak_tersedia']);

        // STEP 4: UPDATE HARGA TOTAL
        $totalHarga = $penyewaan->keranjangs()->sum('harga_sewa');
        $penyewaan->update(['harga_total' => $totalHarga]);

        // REDIRECT KE URL SEBELUMNYA
        return redirect()->to(url()->previous())
                         ->with('success', 'Pesanan berhasil ditambahkan ke keranjang!');
                        
    } catch (\Exception $e) {
        \Log::error('Error store pemesanan: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

      public function daftarArmada(Request $request)
    {
        $query = Armada::with('sopir', 'parkir');

        // Filter berdasarkan jenis
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        // Search berdasarkan merek, kapasitas/bobot, atau deskripsi
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('merek', 'like', '%' . $search . '%')
                  ->orWhere('kapasitas', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $armadas = $query->orderBy('created_at', 'desc')->get();

        // Ambil list jenis untuk filter
        $jenisArmada = Armada::select('jenis')
            ->distinct()
            ->whereNotNull('jenis')
            ->pluck('jenis');

        return view('daftarArmada', compact('armadas', 'jenisArmada'));
    }

    public function register()
    {
        return view('auth.register');
    }
    // Proses register + kirim email verifikasi
   public function registerStore(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'umur' => 'required|integer|min:17|max:100',
        'kata_sandi' => 'required|string|min:8',
        'telepon' => 'nullable|string|max:20',
        'alamat' => 'nullable|string|max:255',
    ], [
        'nama.required' => 'Nama lengkap wajib diisi',
        'email.required' => 'Email wajib diisi',
        'email.unique' => 'Email sudah terdaftar, silakan login',
        'email.email' => 'Format email tidak valid',
        'umur.required' => 'Umur wajib diisi',
        'umur.min' => 'Umur minimal 17 tahun',
        'umur.max' => 'Umur tidak valid',
        'kata_sandi.required' => 'Kata sandi wajib diisi',
        'kata_sandi.min' => 'Kata sandi minimal 8 karakter',
    ]);

    // Buat user baru
    $user = User::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'umur' => $request->umur,
        'kata_sandi' => Hash::make($request->kata_sandi),
        'telepon' => $request->telepon,
        'alamat' => $request->alamat,
        'peran_id' => 2, // Otomatis menjadi Client
    ]);

    // Trigger event untuk kirim email verifikasi
    // menjalnkan even listener SendEmailVerificationNotification 
    event(new Registered($user));

    // Login otomatis setelah register
    Auth::login($user);

    // Redirect ke halaman verifikasi email
    return redirect()->route('verification.notice')
        ->with('success', 'Registrasi berhasil! Silakan cek email untuk verifikasi.');
}
    public function login()
{
    return view('auth.login');
}

public function loginStore(Request $request)
{
    if (empty($request->email) || empty($request->kata_sandi)) {
        return back()->withErrors(['email' => 'Email/Password wajib diisi'])->withInput();
    }

    $request->validate([
        'email' => 'required|email',
        'kata_sandi' => 'required|min:8',
    ], [
        'email.email' => 'Format email tidak valid',
        'kata_sandi.min' => 'Kata sandi minimal 8 karakter',
    ]);

    // ambil user
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->kata_sandi, $user->kata_sandi)) {
        return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->withInput();
    }

    // login via Auth
    Auth::login($user);

    // redirect ke halaman utama
    return redirect('/');
}

}
