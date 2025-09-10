<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'offices';

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
        'front_office_email'
    ];

    /**
     * Each office has many client requests initiated from it
     */
    public function clientRequests()
    {
        return $this->hasMany(ClientRequest::class, 'office_id', 'id');
    }

    /**
     * Each office has many shipment collections
     * (through client requests → requestId)
     */
    public function shipmentCollections()
    {
        return $this->hasManyThrough(
            ShipmentCollection::class,
            ClientRequest::class,
            'office_id',      // Foreign key on client_requests
            'requestId',      // Foreign key on shipment_collections
            'id',             // Local key on offices
            'requestId'       // Local key on client_requests
        );
    }

    /**
     * Each office has many shipment items
     * (through shipment collections)
     */
    public function shipmentItems()
    {
        return $this->hasManyThrough(
            ShipmentItem::class,
            ShipmentCollection::class,
            'origin_id',   // If collections point directly to office (optional)
            'shipment_id', // Link to shipment_items
            'id',          // Local office key
            'id'           // Local shipment_collection key
        );
    }

    public function originShipments()
    {
        return $this->hasMany(ShipmentCollection::class, 'origin_id', 'id');
    }

    public function destinationShipments()
    {
        return $this->hasMany(ShipmentCollection::class, 'destination_id', 'id');
    }

    public function officeUsers()
    {
        return $this->hasMany(OfficeUser::class);
    }
    public function activeFrontOfficeUsers()
    {
        return $this->officeUsers()
            ->where('status', 'active')
            ->with('user'); // eager load the actual User model
    }
    public function users()
    {
        return $this->hasMany(User::class, 'station', 'id');
    }

}
