<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ClientController extends Controller
{
    public function index()
    {
        $clients = User::where('peran_id', 2)->get();
        return view('dashboard.client.index', compact('clients'));
    }

    public function show($id)
    {
        $client = User::where('peran_id', 2)->findOrFail($id);
        return view('dashboard.client.detail', compact('client'));
    }

    public function destroy($id)
    {
        try {
            $client = User::where('peran_id', 2)->findOrFail($id);

            // Hapus gambar di Cloudinary (kalau ada)
            if ($client->gambar) {
                try {
                    $publicId = $this->getPublicIdFromUrl($client->gambar);
                    if ($publicId) {
                        Cloudinary::destroy($publicId);
                    }
                } catch (\Exception $e) {
                    \Log::error('Gagal menghapus gambar Cloudinary Client: ' . $e->getMessage());
                }
            }

            // Hapus data client dari database
            $client->delete();

            return redirect()->route('client.index')->with('success', 'Data client berhasil dihapus!');
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
}
