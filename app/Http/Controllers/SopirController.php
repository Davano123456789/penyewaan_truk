<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SopirController extends Controller
{
       public function index()
    {
        // Ambil semua user dengan peran_id = 3
        $sopirs = User::where('peran_id', 3)->get();

        // Kirim data ke view
        return view('dashboard.daftarSopir.index', compact('sopirs'));
    }
    public function show($id)
    {
        // Ambil data user berdasarkan id dan pastikan peran_id = 3 (sopir)
        $sopir = User::where('id', $id)->where('peran_id', 3)->firstOrFail();

        // Kirim data ke view
        return view('dashboard.daftarSopir.detail', compact('sopir'));
    }
public function destroy($id)
{
    try {
        $sopir = User::findOrFail($id);

        // Jika ada gambar di Cloudinary, hapus dulu
        if ($sopir->gambar) {
            try {
                $publicId = $this->getPublicIdFromUrl($sopir->gambar);
                if ($publicId) {
                    Cloudinary::destroy($publicId);
                }
            } catch (\Exception $e) {
                \Log::error('Gagal menghapus gambar Cloudinary: ' . $e->getMessage());
            }
        }

        // Hapus data dari database
        $sopir->delete();

        return redirect()->route('sopir.index')->with('success', 'Data sopir berhasil dihapus!');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
    }
}

/**
 * Ambil public_id dari URL Cloudinary
 */
private function getPublicIdFromUrl($url)
{
    // Contoh URL: https://res.cloudinary.com/dch7lqtxa/image/upload/v1234567890/profil/abc123.jpg
    // Hasil public_id: profil/abc123
    $pattern = '/\/upload\/(?:v\d+\/)?(.+)\.\w+$/';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return null;
}
public function create()
    {
        return view('dashboard.daftarSopir.tambah');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'umur' => 'nullable|integer|min:18',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->telepon = $validated['telepon'] ?? null;
        $user->alamat = $validated['alamat'] ?? null;
        $user->umur = $validated['umur'] ?? null;
        $user->peran_id = 3; // peran sopir
        $user->kata_sandi = bcrypt($validated['password']); // default password, bisa diubah

        if ($request->hasFile('gambar')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'folder' => 'sopir',
                'transformation' => [
                    'width' => 300,
                    'height' => 300,
                    'crop' => 'limit'
                ]
            ])->getSecurePath();
            $user->gambar = $uploadedFileUrl;
        }

        $user->save();

        return redirect()->route('sopir.index')->with('success', 'Sopir berhasil ditambahkan!');
    }
public function edit($id)
    {
        $sopir = User::where('id', $id)->where('peran_id', 3)->firstOrFail();
        return view('dashboard.daftarSopir.edit', compact('sopir'));
    }

    public function update(Request $request, $id)
    {
        $sopir = User::where('id', $id)->where('peran_id', 3)->firstOrFail();
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $sopir->id,
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'umur' => 'nullable|integer|min:18',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|string|min:6',
        ]);
        $sopir->nama = $validated['nama'];
        $sopir->email = $validated['email'];
        $sopir->telepon = $validated['telepon'] ?? null;
        $sopir->alamat = $validated['alamat'] ?? null;
        $sopir->umur = $validated['umur'] ?? null;
        if ($request->filled('password')) {
            $sopir->kata_sandi = bcrypt($validated['password']);
        }
        if ($request->hasFile('gambar')) {
            $uploadedFileUrl = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload($request->file('gambar')->getRealPath(), [
                'folder' => 'sopir',
                'transformation' => [
                    'width' => 300,
                    'height' => 300,
                    'crop' => 'limit'
                ]
            ])->getSecurePath();
            $sopir->gambar = $uploadedFileUrl;
        }
        $sopir->save();
        return redirect()->route('sopir.index')->with('success', 'Data sopir berhasil diupdate!');
    }
}
