<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorReport extends Model
{
    protected $fillable = ['no_agenda', 'vendor_name', 'vendor_user_id', 'recipient_role', 'checklist', 'catatan'];

    protected $casts = ['checklist' => 'array'];

    public function files()
    {
        return $this->hasMany(VendorReportFile::class);
    }
}
