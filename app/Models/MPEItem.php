<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use App\Models\MPECategory;
use App\Models\MPEUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MPEItem extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'mpe_items';

    protected $fillable = [
        "category_id", "unit_id", "unit", "name",
        "sell_price", "discount_price", "is_discount", "is_promotion",
        "status"
    ];

    protected $casts = [
        "is_discount" => "boolean",
        "is_promotion" => "boolean"
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MPECategory::class, 'category_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(MPEUnit::class, 'unit_id', 'id');
    }

}
