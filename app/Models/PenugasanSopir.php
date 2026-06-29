<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanSopir extends Model
{
    use HasFactory;

    protected $table = 'penugasan_sopirs';

    protected $fillable = [
        'keranjang_id',
        'sopir_id',
        'catatan_penugasan',
        'bukti_selesai'
    ];

    // Relasi ke Keranjang
    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'keranjang_id');
    }

    // Relasi ke User (Sopir)
    public function sopir()
    {
        return $this->belongsTo(User::class, 'sopir_id');
    }
}
