<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeUser extends Model
{
    protected $fillable = [
        'office_id',
        'user_id',
        'status'
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }       
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
