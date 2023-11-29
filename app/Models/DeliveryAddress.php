<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryAddress extends Model
{
    use BasicAudit, HasFactory, SnowflakeID, SoftDeletes;

    protected $fillable = [
        'user_id', 'address', 'contact_phone', 'contact_person', 'is_default',
    ];

    public $table = 'delivery_address';

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

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'delivery_address_id');
    }
}
