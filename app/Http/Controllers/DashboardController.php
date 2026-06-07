<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penyewaan;
use App\Models\Armada;
use Carbon\Carbon;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalAktif = Penyewaan::where('status', 'aktif')->count();
        $totalMenungguPembayaran = Penyewaan::where('status', 'menunggu_pembayaran')->count();
        $totalMenungguKonfirmasi = Penyewaan::where('status', 'menunggu_konfirmasi_pembayaran')->count();

        $totalArmadaTersedia = Armada::where('status', 'aktif')->count();
        $totalArmadaDisewa = Armada::where('status', 'tidak_tersedia')->count();
        $totalArmada = Armada::count();

        $now = Carbon::now();
        $totalPenyewaanBulanIni = Penyewaan::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
        $totalPenyewaan = Penyewaan::count();

        // Laporan Keuangan Interaktif (Owner saja)
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $jenisTruk = $request->query('jenis_truk');
        $statusPembayaran = $request->query('status_pembayaran');
        $jenisPembayaran = $request->query('jenis_pembayaran');

        $laporanPenyewaans = collect();
        $filteredOmset = 0;
        $jenisTrukList = collect();

        if (auth()->check() && auth()->user()->peran_id == 4) {
            $jenisTrukList = Armada::distinct()->pluck('jenis');

            $query = Penyewaan::with(['client', 'pembayaran', 'keranjangs.armada']);

            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }
            if ($jenisTruk) {
                $query->whereHas('keranjangs.armada', function($q) use ($jenisTruk) {
                    $q->where('jenis', $jenisTruk);
                });
            }
            if ($statusPembayaran) {
                $query->whereHas('pembayaran', function($q) use ($statusPembayaran) {
                    $q->where('status', $statusPembayaran);
                });
            }
            if ($jenisPembayaran) {
                $query->whereHas('pembayaran', function($q) use ($jenisPembayaran) {
                    $q->where('jenis', $jenisPembayaran);
                });
            }

            $laporanPenyewaans = $query->orderBy('created_at', 'desc')->get();

            // Hitung total omset dari data terfilter (hanya pembayaran yang lunas)
            $filteredOmset = $laporanPenyewaans->sum(function($p) {
                return ($p->pembayaran && $p->pembayaran->status === 'lunas') ? (float)$p->pembayaran->jumlah_bayar : 0;
            });
        }

        return view('dashboard.dashboard', compact(
            'totalAktif',
            'totalMenungguPembayaran',
            'totalMenungguKonfirmasi',
            'totalArmadaTersedia',
            'totalArmadaDisewa',
            'totalPenyewaanBulanIni',
            'totalPenyewaan',
            'totalArmada',
            'laporanPenyewaans',
            'filteredOmset',
            'jenisTrukList'
        ));
    }
}
