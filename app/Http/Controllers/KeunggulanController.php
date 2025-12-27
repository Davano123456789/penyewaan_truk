<?php

namespace App\Http\Controllers;

use App\Models\Keunggulan;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class KeunggulanController extends Controller
{
    public function index()
    {
        $keunggulans = Keunggulan::orderBy('created_at', 'desc')->get();
        return view('dashboard.keunggulan.index', compact('keunggulans'));
    }

    public function tambah()
    {
        return view('dashboard.keunggulan.tambah');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
                'deskripsi' => 'nullable|string',
            ]);

            if ($request->hasFile('gambar')) {
                $uploadedFileUrl = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                    'folder' => 'keunggulan_images'
                ])->getSecurePath();

                $validated['gambar'] = $uploadedFileUrl;
            }

            Keunggulan::create($validated);

            return redirect()->route('keunggulan.index')->with('success', 'Keunggulan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $keunggulan = Keunggulan::findOrFail($id);
        return view('dashboard.keunggulan.detail', compact('keunggulan'));
    }

    public function edit($id)
    {
        $keunggulan = Keunggulan::findOrFail($id);
        return view('dashboard.keunggulan.edit', compact('keunggulan'));
    }

    public function update(Request $request, $id)
    {
        try {
            $keunggulan = Keunggulan::findOrFail($id);

            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
                'deskripsi' => 'nullable|string',
            ]);

            if ($request->hasFile('gambar')) {
                if ($keunggulan->gambar) {
                    $publicId = $this->getPublicIdFromUrl($keunggulan->gambar);
                    Cloudinary::destroy($publicId);
                }

                $uploadedFileUrl = Cloudinary::upload($request->file('gambar')->getRealPath(), [
                    'folder' => 'keunggulan_images'
                ])->getSecurePath();

                $validated['gambar'] = $uploadedFileUrl;
            }

            $keunggulan->update($validated);

            return redirect()->route('keunggulan.index')->with('success', 'Keunggulan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $keunggulan = Keunggulan::findOrFail($id);

            if ($keunggulan->gambar) {
                $publicId = $this->getPublicIdFromUrl($keunggulan->gambar);
                Cloudinary::destroy($publicId);
            }

            $keunggulan->delete();

            return redirect()->route('keunggulan.index')->with('success', 'Keunggulan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function getPublicIdFromUrl($url)
    {
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        $parts = explode('/', trim($path, '/'));

        $folder = $parts[count($parts) - 2] ?? null;
        $filename = pathinfo(end($parts), PATHINFO_FILENAME);

        if ($folder) {
            return $folder . '/' . $filename;
        }

        return $filename;
    }
}
