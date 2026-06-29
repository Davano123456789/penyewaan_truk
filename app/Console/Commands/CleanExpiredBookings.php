<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PemesananCleanupService;

class CleanExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pembersihan otomatis keranjang/pemesanan menunggu pembayaran yang kadaluwarsa (> 10 menit)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pembersihan pemesanan kadaluwarsa...');
        
        $count = PemesananCleanupService::cleanExpiredBookings();
        
        if ($count > 0) {
            $this->success("Berhasil membersihkan {$count} pemesanan yang kadaluwarsa dan melepas armadanya.");
        } else {
            $this->info('Tidak ada pemesanan kadaluwarsa yang ditemukan.');
        }
        
        return Command::SUCCESS;
    }
}
