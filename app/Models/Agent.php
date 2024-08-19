<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Agent extends Authenticatable implements JWTSubject
{
    use BasicAudit, HasApiTokens, HasFactory, HasPermissions, HasRoles, Notifiable, SnowflakeID, SoftDeletes;

    protected $guard = 'agents';

    protected $fillable = [
        'profile',
        'partner_id',
        'main_agent_id',
        'reference_id',
        'level_one',
        'level_two',
        'level_three',
        'level_four',
        'point',
        'first_name',
        'last_name',
        'dob',
        'nrc',
        'nrc_front',
        'nrc_back',
        'email',
        'prefix',
        'phone',
        'address',
        'country_id',
        'region_or_state_id',
        'city_id',
        'township_id',
        'password',
        'payment_password',
        'email_verified_at',
        'phone_verified_at',
        'kyc_status',
        'status',
        'email_expired_at',
        'email_verify_code',
        'agent_type',
    ];

    protected $casts = [
        'dob' => 'date',
        'password' => 'hashed',
        'payment_password' => 'hashed',
        'level_one' => 'array',
        'level_two' => 'array',
        'level_three' => 'array',
        'level_four' => 'array',
        'email_expired_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'payment_password',
    ];

    public function bankAccounts()
    {
        return $this->hasMany(AgentBankAccount::class, 'agent_id', 'id');
    }

    public function deposit()
    {
        return $this->hasMany(Deposit::class, 'agent_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'agent_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
