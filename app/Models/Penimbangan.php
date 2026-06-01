<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penimbangan extends Model
{
    protected $fillable = [
        'balita_id',
        'tanggal_penimbangan',
        'umur_bulan',
        'berat_badan',
        'tinggi_badan',
        'status_bbu',   
        'status_tbu',   
        'status_bbtb',  
    ];

    public function balita()
    {
        return $this->belongsTo(Balita::class);
    }
}
