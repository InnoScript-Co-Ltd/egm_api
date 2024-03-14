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

class MPEUser extends Authenticatable implements JWTSubject
{
    use BasicAudit, HasApiTokens, HasFactory, HasPermissions, HasRoles, Notifiable, SnowflakeID, SoftDeletes;

    protected $connection = 'mpe';

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'dob',
        'occupation',
        'position',
        'address',
        'password',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'email_code',
        'client_type',
    ];

    protected $appends = ['created_by', 'updated_by'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
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
