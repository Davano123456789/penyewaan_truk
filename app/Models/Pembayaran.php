<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'metode',
        'jenis',
        'jumlah_bayar',
        'tanggal_bayar',
        'bukti_transfer',
        'status',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
    ];

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class);
    }
}