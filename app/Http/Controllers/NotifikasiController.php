<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan halaman daftar semua notifikasi
     */
    public function listAll()
    {
        $notifikasis = Auth::user()->notifikasis()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.notifikasi.index', compact('notifikasis'));
    }

    /**
     * Ambil notifikasi terbaru untuk user yang login (JSON)
     */
    public function index()
    {
        $notifikasis = Auth::user()->notifikasis()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $unreadCount = Auth::user()->notifikasis()
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => $notifikasis,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function read($id)
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())
            ->findOrFail($id);
            
        $notifikasi->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'url' => $notifikasi->url
        ]);
    }

    /**
     * Tandai semua sebagai dibaca
     */
    public function readAll()
    {
        Auth::user()->notifikasis()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Hapus satu notifikasi
     */
    public function destroy($id)
    {
        $notifikasi = Notifikasi::where('user_id', Auth::id())
            ->findOrFail($id);
            
        $notifikasi->delete();

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Hapus semua notifikasi
     */
    public function destroyAll()
    {
        Auth::user()->notifikasis()->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
