<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balita extends Model
{
    protected $fillable = [
        'nama_balita',
        'nama_ibu',
        'tanggal_lahir',
        'jenis_kelamin',
    ];

    public function penimbangans()
    {
        return $this->hasMany(Penimbangan::class);
    }
}
