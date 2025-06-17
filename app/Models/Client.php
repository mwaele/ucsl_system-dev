<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    use HasApiTokens, Notifiable;
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
}
