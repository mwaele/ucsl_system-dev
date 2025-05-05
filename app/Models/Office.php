<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'createdBy',                   
        'name',                        
        'shortName',                   
        'country',                     
        'city',                        
        'longitude',                   
        'latitude',                    
        'type',                        
        'mpesaTill',                   
        'mpesaPaybill',                
        'mpesaTillStkCallBack',        
        'mpesaTillC2bConfirmation',    
        'mpesaTillC2bValidation',      
        'mpesaPaybillStkCallBack',     
        'mpesaPaybillC2bConfirmation', 
        'mpesaPaybillC2bValidation',   
        'approvedBy',                  
        'status',
    ];
}
