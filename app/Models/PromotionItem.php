<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionItem extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    public $table = 'promotion_items';

    protected $fillable = [
        'item_type', 'item_id', 'status',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function promotion_items()
    {
        $this->morphedByMany(Item::class, 'promotion_items');
    }
}
