<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Armada extends Model
{
    use HasFactory;

    protected $table = 'armadas';

    protected $fillable = [
        'no_polisi',
        'sopir_id',
        'parkir_id',
        'merek',
        'jenis',
        'kapasitas',
        'deskripsi',
        'gambar',
        'status'
    ];

    protected $casts = [
        'kapasitas' => 'integer',
    ];

    /**
     * Relasi ke User (Sopir dengan peran_id = 3)
     */
    public function sopir()
    {
        return $this->belongsTo(User::class, 'sopir_id');
    }

    /**
     * Relasi ke Parkir
     */
    public function parkir()
    {
        return $this->belongsTo(Parkir::class, 'parkir_id');
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'aktif' => 'success',
            'maintenance' => 'warning',
            'nonaktif' => 'danger'
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Accessor untuk status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'aktif' => 'Aktif',
            'maintenance' => 'Maintenance',
            'nonaktif' => 'Non-Aktif'
        ];

        return $labels[$this->status] ?? 'Unknown';
    }
}