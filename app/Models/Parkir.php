<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parkir extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'alamat', 'longitude', 'latitude'];
    public function armadas()
{
    return $this->hasMany(Armada::class, 'parkir_id');
}
}
