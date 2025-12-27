<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['penyewaan.client'])->orderBy('tanggal_bayar', 'desc');

        if ($request->has('search') && $request->search != '') {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('metode', 'like', '%' . $s . '%')
                  ->orWhere('jenis', 'like', '%' . $s . '%')
                  ->orWhere('status', 'like', '%' . $s . '%')
                  ->orWhereHas('penyewaan', function($q2) use ($s) {
                      $q2->where('id', 'like', '%' . $s . '%');
                  });
            });
        }

        $pembayarans = $query->paginate(15);

        return view('dashboard.pembayaranAdmin.index', compact('pembayarans'));
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with(['penyewaan.keranjangs.armada', 'penyewaan.client'])
            ->findOrFail($id);

        return view('dashboard.pembayaranAdmin.show', compact('pembayaran'));
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $p = Pembayaran::findOrFail($id);

            if ($p->status != 'lunas') {
                return back()->with('error', 'Hanya pembayaran dengan status lunas yang dapat dihapus!');
            }

            // Hapus file bukti jika disimpan di public
            if ($p->bukti_transfer) {
                $filePath = public_path($p->bukti_transfer);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $p->delete();

            DB::commit();

            return redirect()->route('pembayaranAdmin.index')->with('success', 'Pembayaran berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error hapus pembayaran admin: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }
}
