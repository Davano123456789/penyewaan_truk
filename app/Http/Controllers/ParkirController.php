<?php

namespace App\Http\Controllers;

use App\Models\Parkir;
use Illuminate\Http\Request;

class ParkirController extends Controller
{
    public function index()
{
    $parkirs = Parkir::with(['armadas.sopir'])->get();
    return view('dashboard.parkir.index', compact('parkirs'));
}
public function armada($id)
{
    $parkir = Parkir::with(['armadas.sopir'])->findOrFail($id);
    return view('dashboard.parkir.armada', compact('parkir'));
}
    public function tambah()
    {
        return view('dashboard.parkir.tambah');
    }
    public function destroy($id)
{
    $parkir = Parkir::findOrFail($id);
    $parkir->delete();

    return redirect()->route('parkir.index')->with('success', 'Data parkiran berhasil dihapus!');
}

    public function edit($id)
{
    $parkir = Parkir::findOrFail($id);
    return view('dashboard.parkir.edit', compact('parkir'));
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nama' => 'required',
        'alamat' => 'required',
        'latitude' => 'required',
        'longitude' => 'required',
    ]);

    $parkir = Parkir::findOrFail($id);
    $parkir->update($validated);

    return redirect()->route('parkir.index')->with('success', 'Data parkiran berhasil diupdate!');
}
   public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'alamat' => 'nullable|string',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
    ], [
        'nama.required' => 'Nama parkiran wajib diisi',
        'nama.max' => 'Nama parkiran maksimal 255 karakter',
        'latitude.numeric' => 'Latitude harus berupa angka',
        'latitude.between' => 'Latitude harus antara -90 sampai 90',
        'longitude.numeric' => 'Longitude harus berupa angka',
        'longitude.between' => 'Longitude harus antara -180 sampai 180',
    ]);

    try {
        // Simpan data ke database
        Parkir::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('parkir.index')
            ->with('success', 'Data parkiran berhasil ditambahkan!');
            
    } catch (\Exception $e) {
        // Redirect dengan pesan error
        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
public function show($id)
{
    $parkir = Parkir::with('armadas')->findOrFail($id);
    return view('dashboard.parkir.detail', compact('parkir'));
}

}
