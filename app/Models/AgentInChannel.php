<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentInChannel extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'agent_in_channels';

    protected $fillable = [
        'main_agent_id', 'agent_id', 'channel_id', 'percentage',
    ];

    protected $casts = [
        'percentage' => 'float',
    ];
}
