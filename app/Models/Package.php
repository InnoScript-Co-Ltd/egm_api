<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'packages';

    protected $fillable = [
        'name', 'roi_rate', 'duration', 'deposit_amount', 'status',
    ];

    protected $casts = [
        'deposit_amount' => 'array',
    ];
}
