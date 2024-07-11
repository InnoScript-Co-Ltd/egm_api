<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use BasicAudit, HasFactory, SnowflakeID, SoftDeletes;
    protected $guard = 'agents';
    protected $fillable = [
        'profile',
        'first_name',
        'last_name',
        'dob',
        'nrc',
        'passport',
        'nrc_front',
        'nrc_back',
        'passport_front',
        'passport_back',
        'email',
        'prefix',
        'phone',
        'address',
        'country_id',
        'region_or_state_id',
        'city_id',
        'township_id',
        'password',
        'email_verified_at',
        'phone_verified_at',
        'kyc_status',
        'status'
    ];
    protected $casts = [
        "dob" => "date",
        "password" => "hashed"
    ];

    protected $hidden = [
        "password"
    ];

    public function bankAccounts()
    {
        return $this->hasMany(AgentBankAccount::class,"agent_id", "id");
    }
}
