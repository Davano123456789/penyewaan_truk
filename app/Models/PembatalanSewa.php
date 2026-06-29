<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembatalanSewa extends Model
{
    use HasFactory;

    protected $table = 'pembatalan_sewas';

    protected $fillable = [
        'keranjang_id',
        'alasan_batal',
        'catatan',
        'bukti_refund',
        'nominal_refund'
    ];

    // Relasi ke Keranjang (Detail Sewa)
    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class, 'keranjang_id');
    }
}
