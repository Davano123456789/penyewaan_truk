<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MitraController extends Controller
{
    // Halaman Daftar Mitra
    public function index()
    {
        $mitras = Mitra::orderBy('created_at', 'desc')->get();
        return view('dashboard.mitra.index', compact('mitras'));
    }

    // Halaman Form Tambah Mitra
    public function tambah()
    {
        return view('dashboard.mitra.tambah');
    }

    // Proses Simpan Mitra Baru
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Upload logo ke Cloudinary
            if ($request->hasFile('logo')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('logo')->getRealPath(), [
                    'folder' => 'mitra_logos'
                ])->getSecurePath();
                
                $validated['logo'] = $uploadedFileUrl;
            }

            $validated['user_id'] = auth()->id();

            Mitra::create($validated);

            return redirect()->route('mitra.index')->with('success', 'Mitra berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Halaman Detail Mitra
    public function show($id)
    {
        $mitra = Mitra::findOrFail($id);
        return view('dashboard.mitra.detail', compact('mitra'));
    }

    // Halaman Form Edit Mitra
    public function edit($id)
    {
        $mitra = Mitra::findOrFail($id);
        return view('dashboard.mitra.edit', compact('mitra'));
    }

    // Proses Update Mitra
    public function update(Request $request, $id)
    {
        try {
            $mitra = Mitra::findOrFail($id);

            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Jika ada logo baru
            if ($request->hasFile('logo')) {
                // Hapus logo lama dari Cloudinary (opsional)
                if ($mitra->logo) {
                    $publicId = $this->getPublicIdFromUrl($mitra->logo);
                    Cloudinary::destroy($publicId);
                }

                // Upload logo baru
                $uploadedFileUrl = Cloudinary::upload($request->file('logo')->getRealPath(), [
                    'folder' => 'mitra_logos'
                ])->getSecurePath();
                
                $validated['logo'] = $uploadedFileUrl;
            }

            $validated['user_id'] = auth()->id();

            $mitra->update($validated);

            return redirect()->route('mitra.index')->with('success', 'Mitra berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Proses Hapus Mitra
    public function destroy($id)
{
    try {
        $mitra = Mitra::findOrFail($id);

        if ($mitra->logo) {
            $publicId = $this->getPublicIdFromUrl($mitra->logo);
            Cloudinary::destroy($publicId);
        }

        $mitra->delete();

        return redirect()->route('mitra.index')->with('success', 'Mitra berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    // Helper: Extract Public ID dari Cloudinary URL
   private function getPublicIdFromUrl($url)
{
    $parsedUrl = parse_url($url);
    $path = $parsedUrl['path']; // contoh: /mitra_logos/abc123.jpg
    $parts = explode('/', $path);

    // ambil dua terakhir (folder + nama file)
    $folder = $parts[count($parts) - 2];
    $filename = pathinfo(end($parts), PATHINFO_FILENAME);

    return $folder . '/' . $filename; // hasil: mitra_logos/abc123
}

}