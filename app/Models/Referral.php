<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referral extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'referrals';

    protected $fillable = [
        'agent_id',
        'main_agent_id',
        'partner_id',
        'register_agents',
        'agent_type',
        'link',
        'count',
        'commission',
        'referral_type',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'date',
        'register_agents' => 'array',
    ];

    public function referralPartner()
    {
        return $this->hasMany(ReferralPartner::class, 'referral_id', 'id');
    }
}
