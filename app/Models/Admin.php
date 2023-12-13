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

class Admin extends Authenticatable implements JWTSubject
{
    use BasicAudit, HasApiTokens, HasFactory,  HasPermissions, HasRoles, Notifiable, SnowflakeID, SoftDeletes;

    protected $table = 'admins';

    protected $guard = 'dashboard';

    protected $appends = ['created_by', 'updated_by', 'rnp'];

    protected $fillable = [
        'name', 'profile', 'email', 'phone', 'password', 'status', 'email_verified_at', 'phone_verified_at',
    ];

    protected $hidden = [
        'password', 'roles',
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

    protected function getRnpAttribute()
    {
        $role = $this->roles->first();
        
        return [
            'role' => $role->name,
            'permissions' => $role->permissions->pluck('name'),
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
