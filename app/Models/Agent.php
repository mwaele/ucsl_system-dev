<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'request_id',
        'agent_name',
        'agent_id_no',
        'agent_phone_no',
        'agent_reason',
        'agent_requested',
        'agent_approved',
        'agent_declined',
        'agent_approval_remarks',
        'agent_approved_at',
        'agent_approved_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'agent_requested' => 'boolean',
        'agent_approved' => 'boolean',
        'agent_declined' => 'boolean',
        'agent_approved_at' => 'datetime',
    ];

    /**
     * Optional: relationship if an agent is approved by a user.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'agent_approved_by');
    }

    public function shipment()
    {
        return $this->belongsTo(ShipmentCollection::class, 'request_id');
    }

    public function clientRequest()
    {
        return $this->belongsTo(ClientRequest::class, 'request_id', 'requestId');
    }

}
