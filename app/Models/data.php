<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data extends Model
{
    use HasFactory;

    protected $table = 'data';

    protected $fillable = [
        'dtl',
        'ulp',
        'transaksi',
        'status',
        'no_agenda',
        'alamat',
        'tarif_lama',
        'daya_lama',
        'tarif_baru',
        'daya_baru'
    ];
    
}
