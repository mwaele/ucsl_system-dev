<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountsReceivableMain extends Model
{
    protected $table = 'accounts_receivables_main';

    protected $fillable = [
        'client_id', 'balance', 'current', '30_days', '60_days', '90_days'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function transactions()
    {
        return $this->hasMany(AccountsReceivableTransaction::class, 'client_id', 'client_id');
    }
}
