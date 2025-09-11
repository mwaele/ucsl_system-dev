<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    protected $guard = 'client';
    //  use HasApiTokens, Notifiable;

    protected $fillable = [
        'accountNo',
        'name',
        'email',
        'password',
        'contact',
        'address',
        'city',
        'building',
        'country',
        'category',
        'contactPerson',
        'contactPersonPhone',
        'contactPersonEmail',
        'type',
        'industry',
        'kraPin',
        'postalCode',
        'status',
        'verificationCode',
        'special_rates_status',
        'sales_person_id',
        'otp',
        'verified_otp',
        'role'  // Added role field
    ];
    /**
     * The attributes that should be hidden for arrays (e.g., when returned in API).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function tracks()
    {
        return $this->hasMany(Track::class, 'clientId');
    }

    public function requests()
    {
        return $this->hasMany(ClientRequest::class, 'clientId');
    }

    public function shipmentCollections()
    {
        return $this->hasMany(ShipmentCollection::class, 'client_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'client_id', 'id');
    }

    public function shipmentItems()
    {
        return $this->hasManyThrough(
            ShipmentItem::class,
            ShipmentCollection::class,
            'client_id',   // Foreign key on shipment_collections
            'shipment_id', // Foreign key on shipment_items
            'id',          // Local key on clients
            'client_id'    // Local key on shipment_collections
        );
    }
}
