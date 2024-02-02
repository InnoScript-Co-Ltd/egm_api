<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use App\Models\User;
use App\Models\MemberCard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'members';

    protected $fillable = [
        'user_id', 'member_id', 'membercard_id', 'amount', 'expired_at', 'status',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public $appends = ['user_name','membercard_name'];

    protected function getUserNameAttribute()
    {
        $user = User::where(['id' => $this->attributes['user_id']])->first();
        if ($user) {
            return $user->name;
        } else {
            return null;
        }
    }

    protected function getMembercardNameAttribute()
    {
        $memberCard = MemberCard::where(['id' => $this->attributes['membercard_id']])->first();
        if ($memberCard) {
            return $memberCard->label;
        } else {
            return null;
        }
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    
}
