<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $fillable = [
        'delivery_address_id', 'user_id', 'user_name', 'phone', 'email',
        'delivery_address', 'delivery_contact_person', 'delivery_contact_phone',
        'discount', 'delivery_feed', 'total_amount', 'items', 'payment_type', 'status',
    ];

    public $table = 'orders';

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function deliveryAddress(): BelongsTo
    {
        return $this->belongsTo(DeliveryAddress::class, 'delivery_address_id', 'id');
    }

    protected $casts = [
        'items' => 'array',
    ];
}
