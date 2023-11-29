<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Region;

class Shop extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    public $table = "shops";

    protected $fillable = [
        "region_id","name","phone","address","location","status"
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}
