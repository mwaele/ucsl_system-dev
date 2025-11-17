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
        'shipment_id',   
        'user_id',  
        'ownedBy', 
    ];

    public function shipment(){
        return $this->belongsTo('App\Models\Shipment');
    }
    
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function company()
    {
        return $this->belongsTo(CompanyInfo::class, 'ownedBy');
    }
    
}
