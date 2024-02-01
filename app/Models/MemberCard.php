<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberCard extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $fillable = [
        'label', 'discount_id', 'front_background', 'back_background', 'expired_at', 'status',
    ];

    protected $table = 'membercards';

    protected $casts = [
        'expired_at' => 'datetime',
    ];
}
