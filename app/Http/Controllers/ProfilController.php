<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfilController extends Controller
{
    /**
     * Display the user's profile
     */
    public function index()
    {
        return view('dashboard.profil.index');
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'umur' => 'nullable|integer|min:17|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'email.email' => 'Format email tidak valid',
            'umur.min' => 'Umur minimal 17 tahun',
            'umur.max' => 'Umur tidak valid',
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
                        'folder' => 'profil',
                        'transformation' => [
                            'width' => 500,
                            'height' => 500,
                            'crop' => 'fill',
                            'gravity' => 'face'
                        ]
                    ]
                )->getSecurePath();
                
                $validated['gambar'] = $uploadedFileUrl;

                // Hapus gambar lama dari Cloudinary jika ada
                if ($user->gambar) {
                    try {
                        // Extract public_id dari URL
                        $publicId = $this->getPublicIdFromUrl($user->gambar);
                        if ($publicId) {
                            Cloudinary::destroy($publicId);
                        }
                    } catch (\Exception $e) {
                        // Log error tapi tetap lanjutkan proses
                        \Log::error('Gagal menghapus gambar lama: ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengupload gambar: ' . $e->getMessage())
                           ->withInput();
            }
        }

        // Update data user
        try {
            $user->update($validated);
            
            return redirect()->route('profil.index')
                           ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Extract public_id from Cloudinary URL
     */
    private function getPublicIdFromUrl($url)
    {
        // Contoh URL: https://res.cloudinary.com/dch7lqtxa/image/upload/v1234567890/profil/abc123.jpg
        // Public ID: profil/abc123
        
        $pattern = '/\/upload\/(?:v\d+\/)?(.+)\.\w+$/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}