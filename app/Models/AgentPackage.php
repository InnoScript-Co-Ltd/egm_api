<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentPackage extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'agent_packages';

    protected $fillable = [
        'agent_id',
        'package_id',
        'package_name',
        'package_roi_rate',
        'package_duration',
        'package_deposit_rate',
        'agent_name',
        'agent_phone',
        'agent_email',
        'exchange_rate',
        'depost_amount',
        'package_start_at',
        'package_expired_at',
        'status',
    ];

    protected $casts = [
        'package_start_at' => 'datetime',
        'package_expired_at' => 'datetime',
    ];
}
