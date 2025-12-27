<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'sopir_id',  // Ini juga merujuk ke users.id
        'armada_id',
        'tanggal_mulai',
        'bukti_selesai',
        'harga_sewa',
        'total_jarak',
        'estimasi_hari',
        'tempat_jemput',
        'tempat_antar',
        'barang_muatan',
        'bobot',
        'alasan_batal',
        'bukti_refund',
        'nominal_refund',
        'latitude_penjemputan',
        'longitude_penjemputan',
        'latitude_antar',
        'longitude_antar',
        'parkir_latitude',
        'parkir_longitude',
        'status'
    ];

    // Relasi ke Penyewaan
    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class);
    }

    // Relasi ke Armada
    public function armada()
    {
        return $this->belongsTo(Armada::class);
    }

    // Relasi ke User (Sopir) - langsung dari keranjang
    public function sopir()
    {
        return $this->belongsTo(User::class, 'sopir_id');
    }
}