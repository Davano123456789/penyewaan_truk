<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',    // Relasi ke users (meskipun namanya client_id)
        'harga_total',
        'status'
    ];

    // Relasi ke User (kolom client_id → table users)
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Relasi ke Keranjang
    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'penyewaan_id');
    }

    // Accessor untuk menghitung total harga item yang AKTIF saja (tidak dibatalkan)
    public function getHargaTotalAktifAttribute()
    {
        return $this->keranjangs
            ->where('status', '!=', 'dibatalkan')
            ->sum('harga_sewa');
    }

    public function pembayaran()
{
    return $this->hasOne(Pembayaran::class);
}
}