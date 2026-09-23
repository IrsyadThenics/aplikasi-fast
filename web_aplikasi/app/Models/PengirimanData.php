<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengirimanData extends Model
{
    protected $table = 'pengiriman_data';
    protected $guarded = [];

    protected $casts = [
        'detail_perluasan' => 'array',
    ];

    public function berkas()
    {
        return $this->hasMany(BerkasDokumen::class, 'pengiriman_id');
    }
}
