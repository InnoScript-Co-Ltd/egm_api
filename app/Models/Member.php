<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
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

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function membercard()
    {
        return $this->hasOne(MemberCard::class, 'id', 'membercard_id');
    }
}
