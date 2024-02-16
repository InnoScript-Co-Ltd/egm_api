<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberOrder extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'membership_orders';

    protected $fillable = [
        'member_id', 'user_id', 'order_number', 'card_type', 'card_number', 'name', 'phone',
        'email', 'status', 'amount', 'discount', 'pay_amount', 'is_wallet',
    ];

    protected $casts = [
        'is_wallet' => 'boolean',
    ];

    public $appends = ['user_name'];

    protected function getUserNameAttribute()
    {
        $user = User::where(['id' => $this->attributes['user_id']])->first();
        if ($user) {
            return $user->name;
        } else {
            return null;
        }
    }
}
