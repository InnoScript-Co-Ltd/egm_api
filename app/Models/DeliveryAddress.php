<?php

namespace App\Models;

use App\Traits\SnowflakeID;
use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Order;

class DeliveryAddress extends Model
{
    use HasFactory, SnowflakeID, BasicAudit, SoftDeletes;

    protected $fillable = [
        "user_id", "address", "contact_phone", "contact_person", "is_default"
    ];

    public $table = "delivery_address";

    public function users():BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function order()
    {
        return $this->hasOne(Order::class, "id", "delivery_address_id");
    }

}
