<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispatcher extends Model
{
    protected $fillable = [ 
        'name',
        'id_no',
        'phone_no',
        'office_id',
        'signature',
        'status'
    ];

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
}
