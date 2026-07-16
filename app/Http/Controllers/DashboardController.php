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

        $totalArmadaTersedia = Armada::where('status', 'tersedia')->count();
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
        $monthlyIncomeData = [];

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

            // Hitung total omset bersih dari data terfilter (pembayaran lunas dikurangi total refund)
            $filteredOmset = $laporanPenyewaans->sum(function($p) {
                if ($p->pembayaran && $p->pembayaran->status === 'lunas') {
                    $totalRefund = 0;
                    foreach ($p->keranjangs as $item) {
                        if ($item->status === 'dibatalkan' && $item->pembatalan) {
                            $totalRefund += (float)$item->pembatalan->nominal_refund;
                        }
                    }
                    return max(0.0, (float)$p->pembayaran->jumlah_bayar - $totalRefund);
                }
                return 0.0;
            });

            // Grafik Pemasukan Bulanan (Owner saja) untuk tahun berjalan
            $monthlyIncome = array_fill(1, 12, 0);
            $allPenyewaanCurrentYear = Penyewaan::whereYear('created_at', $now->year)
                ->with(['pembayaran', 'keranjangs.pembatalan'])
                ->get();
                
            foreach ($allPenyewaanCurrentYear as $penyewaan) {
                if ($penyewaan->pembayaran && $penyewaan->pembayaran->status === 'lunas') {
                    $month = $penyewaan->created_at->month;
                    $totalRefund = 0;
                    foreach ($penyewaan->keranjangs as $item) {
                        if ($item->status === 'dibatalkan' && $item->pembatalan) {
                            $totalRefund += (float)$item->pembatalan->nominal_refund;
                        }
                    }
                    $netIncome = max(0.0, (float)$penyewaan->pembayaran->jumlah_bayar - $totalRefund);
                    $monthlyIncome[$month] += $netIncome;
                }
            }
            $monthlyIncomeData = array_values($monthlyIncome);
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
            'jenisTrukList',
            'monthlyIncomeData'
        ));
    }
}
