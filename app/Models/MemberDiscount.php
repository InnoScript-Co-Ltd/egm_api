<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberDiscount extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'member_discounts';

    protected $fillable = [
        'label', 'discount_percentage', 'discount_fix_amount', 'expend_limit', 'is_expend_limit', 'is_fix_amount', 'start_date',
        'end_date', 'status',
    ];

    protected $casts = [
        'is_expend_limit' => 'boolean',
        'is_fix_amount' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
