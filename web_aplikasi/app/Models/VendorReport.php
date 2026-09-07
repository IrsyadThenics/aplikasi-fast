<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorReport extends Model
{
    protected $fillable = ['no_agenda', 'vendor_name', 'checklist', 'catatan'];

    protected $casts = ['checklist' => 'array'];

    public function files()
    {
        return $this->hasMany(VendorReportFile::class);
    }
}
