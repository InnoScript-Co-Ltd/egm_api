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
        'label', 'discount_id', 'description', 'expired_at', 'status',
    ];

    protected $table = 'membercards';

    // public $appends = ['discount_name'];

    protected $casts = [
        'expired_at' => 'date',
    ];

    // protected function getDiscountNameAttribute()
    // {
    //     $discount = MemberDiscount::where(['id' => $this->attributes['discount_id']])->first();
    //     if ($discount) {
    //         return $discount->label;
    //     } else {
    //         return null;
    //     }
    // }

    public function discounts()
    {
        return $this->hasMany(MemberDiscount::class, 'id', 'discount_id');
    }
}
