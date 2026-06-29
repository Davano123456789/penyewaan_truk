<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['penyewaan.client'])->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('metode', 'like', '%' . $s . '%')
                  ->orWhere('jenis', 'like', '%' . $s . '%')
                  ->orWhere('status', 'like', '%' . $s . '%')
                  ->orWhereHas('penyewaan', function($q2) use ($s) {
                      $q2->where('id', 'like', '%' . $s . '%')
                        ->orWhere('kode_transaksi', 'like', '%' . $s . '%')
                        ->orWhereHas('client', function($q3) use ($s) {
                            $q3->where('nama', 'like', '%' . $s . '%');
                        });
                  });
            });
        }

        $pembayarans = $query->get();

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
        abort(403, 'Penghapusan pembayaran tidak diizinkan sistem.');
    }
}
