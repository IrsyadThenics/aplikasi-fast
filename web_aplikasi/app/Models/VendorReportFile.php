<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorReportFile extends Model
{
    protected $fillable = ['vendor_report_id', 'nama_file', 'path_file'];

    public function report()
    {
        return $this->belongsTo(VendorReport::class, 'vendor_report_id');
    }
}
