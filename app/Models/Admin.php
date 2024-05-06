<?php

namespace App\Models;

use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, HasPermissions, HasRoles, Notifiable, SnowflakeID, SoftDeletes;

    protected $table = 'admins';

    protected $guard = 'dashboard';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role_id',
        'dob',
        'address',
        'position',
        'department',
        'nrc',
        'join_date',
        'leave_date',
        'salary',
        'password',
        'status',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $hidden = [
        'password',
        'roles',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'join_date' => 'date',
        'leave_date' => 'date',
        'dob' => 'date',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', 'image');
    }

    protected function getRnpAttribute()
    {
        $role = $this->roles->first();

        return [
            'role' => $role ? $role->name : null,
            'permissions' => $role ? $role->permissions->pluck('name') : [],
        ];
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
