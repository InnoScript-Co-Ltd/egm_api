<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubAgent extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'sub_agents';

    protected $fillable = [
        'agent_id', 'first_name', 'last_name', 'nrc', 'nrc_front', 'nrc_back', 'phone', 'email', 'roi_rate', 'status',
    ];
}
