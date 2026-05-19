<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuteKeranjang extends Model
{
    use HasFactory;

    protected $table = 'rute_keranjangs';

    protected $fillable = [
        'keranjang_id',
        'tempat_jemput',
        'tempat_antar',
        'latitude_penjemputan',
        'longitude_penjemputan',
        'latitude_antar',
        'longitude_antar',
        'parkir_latitude',
        'parkir_longitude',
        'total_jarak',
    ];

    /**
     * Relasi ke model Keranjang (One-to-One Belongs To)
     */
    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'keranjang_id');
    }
}
