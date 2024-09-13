<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'deposits';

    protected $fillable = [
        'agent_id', 'partner_id', 'deposit_amount', 'roi_amount', 'commission_amount', 'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];
}
