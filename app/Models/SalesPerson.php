<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesPerson extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'id_no',
        'type',
        'remarks',
        'status',
        'created_by',
        'office_id',
        'email',
    ];
    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
