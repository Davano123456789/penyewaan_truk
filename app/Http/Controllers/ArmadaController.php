<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Armada;
use App\Models\Parkir;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ArmadaController extends Controller
{
  public function index()
{
    // Ambil semua data armada beserta relasi sopir
    $armadas = Armada::with('sopir')->get();

    // Kirim ke view
    return view('dashboard.armada.index', compact('armadas'));
}

     public function tambah()
    {
        // Ambil user dengan peran_id = 3 (Sopir) yang belum memiliki armada
        $sopirs = User::where('peran_id', 3)->whereDoesntHave('armada')->get();
        $parkirs = Parkir::all();
        return view('dashboard.armada.tambah', compact('sopirs', 'parkirs'));
    }
    
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'no_polisi' => 'required|string|max:255|unique:armadas,no_polisi',
            'sopir_id' => 'nullable|exists:users,id|unique:armadas,sopir_id',
            'parkir_id' => 'nullable|exists:parkirs,id',
            'merek' => 'nullable|string|max:255',
            'jenis' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable|in:tersedia,tidak_tersedia,perawatan'
        ], [
            'no_polisi.required' => 'No Polisi wajib diisi',
            'no_polisi.unique' => 'No Polisi sudah terdaftar',
            'sopir_id.exists' => 'Sopir tidak ditemukan',
            'sopir_id.unique' => 'Sopir sudah ditugaskan ke armada lain',
            'parkir_id.exists' => 'Lokasi parkir tidak ditemukan',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Upload gambar ke Cloudinary jika ada
        if ($request->hasFile('gambar')) {
            try {
                $uploadedFileUrl = Cloudinary::upload(
                    $request->file('gambar')->getRealPath(),
                    [
                        'folder' => 'armada',
                        'transformation' => [
                            'width' => 800,
                            'height' => 600,
                            'crop' => 'limit'
                        ]
                    ]
                )->getSecurePath();
                
                $validated['gambar'] = $uploadedFileUrl;
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengupload gambar: ' . $e->getMessage())
                           ->withInput();
            }
        }

        // Set default status jika tidak ada
        if (!isset($validated['status'])) {
            $validated['status'] = 'tersedia';
        }

        // Simpan ke database
        try {
            Armada::create($validated);
            
            return redirect()->route('armada.index')
                           ->with('success', 'Armada berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                       ->withInput();
        }
    }
 public function edit($id)
{
    $armada = Armada::findOrFail($id);
    // Ambil sopir yang belum memiliki armada ATAU sopir yang sedang ditugaskan di armada ini
    $sopirs = User::where('peran_id', 3)
        ->where(function ($query) use ($armada) {
            $query->whereDoesntHave('armada')
                  ->orWhere('id', $armada->sopir_id);
        })
        ->get();
    $parkirs = Parkir::all();
    return view('dashboard.armada.edit', compact('armada', 'sopirs', 'parkirs'));
}

public function update(Request $request, $id)
{
    $armada = Armada::findOrFail($id);

    $validated = $request->validate([
        'no_polisi' => 'required|string|max:20',
        'merek' => 'nullable|string|max:50',
        'jenis' => 'nullable|string|max:50',
        'kapasitas' => 'nullable|numeric',
        'status' => 'required|string',
        'deskripsi' => 'nullable|string',
        'sopir_id' => 'nullable|exists:users,id|unique:armadas,sopir_id,' . $id,
        'parkir_id' => 'nullable|exists:parkirs,id',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        'sopir_id.unique' => 'Sopir sudah ditugaskan ke armada lain',
    ]);

    // Upload gambar baru jika ada
    if ($request->hasFile('gambar')) {
        $uploadResult = cloudinary()->upload($request->file('gambar')->getRealPath(), [
            'folder' => 'armada',
        ]);
        $validated['gambar'] = $uploadResult->getSecurePath();
    }

    $armada->update($validated);

    return redirect()->route('armada.index')->with('success', 'Data armada berhasil diperbarui!');
}

    public function destroy($id)
{
    $armada = Armada::findOrFail($id);
    $armada->delete();

    return redirect()->route('armada.index')->with('success', 'Data armada berhasil dihapus!');
}
public function show($id)
{
    $armada = Armada::with(['sopir', 'parkir'])->findOrFail($id);
    return view('dashboard.armada.detail', compact('armada'));
}
}
