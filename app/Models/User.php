<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use BasicAudit, HasApiTokens, HasFactory, Notifiable, SnowflakeID, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'phone', 'gender', 'dob', 'occupation', 'position', 'address',
        'password', 'status', 'email_verified_at', 'phone_verified_at', 'email_verify_code', 'email_expired_at', 'client_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'email_expired_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date',
    ];

    // public function images()
    // {
    //     return $this->morphMany(Image::class, 'imageable');
    // }

    public function profile()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', 'profile');
    }

    public function deliveryAddress()
    {
        return $this->hasOne(DeliveryAddress::class, 'id', 'user_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'id', 'user_id');
    }

    public function members()
    {
        return $this->hasMany(Member::class, 'user_id', 'id');
    }

    protected function getCreatedByAttribute()
    {
        $user = null;

        if (auth('dashboard')->id()) {
            $user = Admin::where(['id' => $this->attributes['created_by']])->first();
        }

        if (auth('api')->id()) {
            $user = User::where(['id' => $this->attributes['created_by']])->first();
        }

        if ($user) {
            return ['name' => $user->name, 'id' => $user->id];
        } else {
            return null;
        }
    }

    protected function getUpdatedByAttribute()
    {
        $user = null;

        if (auth('dashboard')->id()) {
            $user = Admin::where(['id' => $this->attributes['updated_by']])->first();
        }

        if (auth('api')->id()) {
            $user = User::where(['id' => $this->attributes['updated_by']])->first();
        }

        if ($user) {
            return ['name' => $user->name, 'id' => $user->id];
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
