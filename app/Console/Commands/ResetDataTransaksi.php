<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDataTransaksi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-transaksi {--force : Lewati konfirmasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengosongkan semua data transaksi (penyewaan, pembayaran, pembatalan, penugasan, notifikasi) tetapi tetap mempertahankan armada, parkiran, user, dll.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('Apakah Anda yakin ingin mengosongkan semua data transaksi? Tindakan ini tidak dapat dibatalkan.')) {
            $this->info('Tindakan dibatalkan.');
            return Command::SUCCESS;
        }

        $this->info('Memulai pembersihan data transaksi...');

        try {
            // Matikan foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Truncate tabel-tabel transaksi
            DB::table('pembatalan_sewas')->truncate();
            $this->comment('Tabel pembatalan_sewas telah dikosongkan.');

            DB::table('penugasan_sopirs')->truncate();
            $this->comment('Tabel penugasan_sopirs telah dikosongkan.');

            DB::table('pembayarans')->truncate();
            $this->comment('Tabel pembayarans telah dikosongkan.');

            DB::table('rute_keranjangs')->truncate();
            $this->comment('Tabel rute_keranjangs telah dikosongkan.');

            DB::table('keranjangs')->truncate();
            $this->comment('Tabel keranjangs telah dikosongkan.');

            DB::table('notifikasis')->truncate();
            $this->comment('Tabel notifikasis telah dikosongkan.');

            DB::table('penyewaans')->truncate();
            $this->comment('Tabel penyewaans telah dikosongkan.');

            // Aktifkan kembali foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Reset status armada yang tidak dalam perawatan agar tersedia
            DB::table('armadas')
                ->where('status', '!=', 'perawatan')
                ->update(['status' => 'tersedia']);
            $this->comment('Status armada telah direset ke tersedia (kecuali yang sedang perawatan).');

            $this->info('Semua data transaksi berhasil dikosongkan secara aman.');
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error('Terjadi kesalahan saat mengosongkan data: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
