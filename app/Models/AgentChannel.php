<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentChannel extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'agent_channels';

    protected $fillable = [
        'agent_id', 'name', 'percentage_pattern', 'max_agent', 'percentage',
    ];

    protected $casts = [
        'percentage' => 'array',
    ];

    public function agentInChannel()
    {
        return $this->hasMany(AgentInChannel::class, 'channel_id', 'id');
    }
}
