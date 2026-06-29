<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'armada_id',
        'tanggal_mulai',
        'harga_sewa',
        'estimasi_hari',
        'barang_muatan',
        'bobot',
        'status',
        'kode_keranjang'
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

    // Relasi ke User (Sopir) - lewat penugasan_sopirs
    public function sopir()
    {
        return $this->hasOneThrough(User::class, PenugasanSopir::class, 'keranjang_id', 'id', 'id', 'sopir_id');
    }

    // Accessor untuk backward compatibility jika memanggil $keranjang->sopir_id secara langsung
    public function getSopirIdAttribute()
    {
        return $this->penugasan->sopir_id ?? null;
    }

    // Relasi ke Pembatalan Sewa
    public function pembatalan()
    {
        return $this->hasOne(PembatalanSewa::class, 'keranjang_id');
    }

    // Relasi ke Penugasan Sopir
    public function penugasan()
    {
        return $this->hasOne(PenugasanSopir::class, 'keranjang_id');
    }

    // Relasi ke Rute Perjalanan
    public function rute()
    {
        return $this->hasOne(RuteKeranjang::class, 'keranjang_id');
    }
}