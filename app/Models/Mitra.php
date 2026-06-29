<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;
    protected $table = 'mitra_kerjas';
    protected $fillable = [
        'user_id',
        'nama',
        'logo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
