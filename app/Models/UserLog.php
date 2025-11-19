<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = [
        'name',
        'actions',
        'url',
        'reference_id',
        'table',
        'user_id'
    ];
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
