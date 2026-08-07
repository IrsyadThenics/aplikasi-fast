<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class uploadData extends Model
{
    protected $table = 'upload_data';

    protected $fillable = [
        'nama_file',
        'path_file',
    ];
}
