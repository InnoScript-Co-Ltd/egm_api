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

    protected $appends = ['created_by', 'updated_by'];

    protected $fillable = [
        'name', 'profile', 'email', 'phone', 'password', 'status', 'email_verified_at', 'phone_verified_at',
    ];

    protected function getCreatedByAttribute()
    {
        $admin = Admin::where(['id' => $this->attributes['created_by']])->first();

        if ($admin) {
            return ['name' => $admin->name, 'id' => $admin->id];
        } else {
            return null;
        }
    }

    protected function getUpdatedByAttribute()
    {
        $admin = Admin::where(['id' => $this->attributes['created_by']])->first();

        if ($admin) {
            return ['name' => $admin->name, 'id' => $admin->id];
        } else {
            return null;
        }
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
