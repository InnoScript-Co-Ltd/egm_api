<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionInItem extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'items_in_promotion';

    protected $fillable = [
        'promotion_id', 'item_id', 'promotion_price', 'status',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public $appends = ['item_name'];

    protected function getItemNameAttribute()
    {
        $item = Item::where(['id' => $this->attributes['item_id']])->first();
        if ($item) {
            return $item->name;
        } else {
            return null;
        }
    }

    public function promotion()
    {
        return $this->hasOne(Promotion::class, 'id', 'promotion_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
