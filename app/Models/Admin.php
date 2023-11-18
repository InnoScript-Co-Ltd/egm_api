<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use BasicAudit, HasApiTokens, HasFactory, Notifiable, SnowflakeID, SoftDeletes;

    protected $table = 'admins';

    protected $guard = 'dashboard';

    protected $fillable = [
        'name', 'profile', 'email', 'phone', 'password', 'status', 'email_verified_at', 'phone_verified_at',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
