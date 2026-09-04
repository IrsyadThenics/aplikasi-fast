<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerkasDokumen extends Model
{
    protected $table = 'berkas_dokumen';
    protected $guarded = [];

    public function pengiriman()
    {
        return $this->belongsTo(PengirimanData::class, 'pengiriman_id');
    }
}
