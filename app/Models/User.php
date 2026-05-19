<?php

namespace App\Models;

use App\Models\Peran;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'alamat',
        'telepon',
        'umur',
        'peran_id',
        'gambar',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Karena pakai kata_sandi bukan password
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }
    public function penyewaans()
    {
       return $this->hasMany(Penyewaan::class, 'client_id');
    }
    // app/Models/User.php
public function peran()
{
    return $this->belongsTo(Peran::class);
}

public function notifikasis()
{
    return $this->hasMany(Notifikasi::class);
}

public function keunggulans()
{
    return $this->hasMany(Keunggulan::class);
}

public function mitras()
{
    return $this->hasMany(Mitra::class);
}

}
