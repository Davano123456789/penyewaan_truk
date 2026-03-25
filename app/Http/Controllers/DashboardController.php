<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penyewaan;
use App\Models\Armada;
use Carbon\Carbon;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalAktif = Penyewaan::where('status', 'aktif')->count();
        $totalMenungguPembayaran = Penyewaan::where('status', 'menunggu_pembayaran')->count();
        $totalMenungguKonfirmasi = Penyewaan::where('status', 'menunggu_konfirmasi_pembayaran')->count();

        $totalArmadaTersedia = Armada::where('status', 'aktif')->count();
        $totalArmadaDisewa = Armada::where('status', 'tidak_tersedia')->count();

        $now = Carbon::now();
        $totalPenyewaanBulanIni = Penyewaan::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        // Omset: total pemasukan per bulan dari pembayaran yang sudah 'lunas'
        $months = [
            'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
        ];

        $omsetData = [];
        for ($m = 1; $m <= 12; $m++) {
            $sum = Pembayaran::where('status', 'lunas')
                ->whereYear('tanggal_bayar', $now->year)
                ->whereMonth('tanggal_bayar', $m)
                ->sum('jumlah_bayar');

            // convert to float (or integer) for chart
            $omsetData[] = (float) $sum;
        }

        return view('dashboard.dashboard', compact(
            'totalAktif',
            'totalMenungguPembayaran',
            'totalMenungguKonfirmasi',
            'totalArmadaTersedia',
            'totalArmadaDisewa',
            'totalPenyewaanBulanIni',
            'months',
            'omsetData'
        ));
    }
}
