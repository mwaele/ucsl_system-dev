<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'regNo',       
        'type',        
        'color',       
        'tonnage',     
        'status',      
        'description', 
        'user_id',     
        'ownedBy', 
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
