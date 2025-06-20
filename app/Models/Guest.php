<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Guest  extends Authenticatable
{
    protected $guard = 'guest';
  //  use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'phone',
        'email'
    ];
}
