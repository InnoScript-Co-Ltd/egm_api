<?php

namespace App\Models;

use App\Traits\SnowflakeID;
use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;

class Item extends Model
{
    use HasFactory,SnowflakeID,BasicAudit,SoftDeletes;

    protected $fillable = [
        "category_id","name","code","description","content","price","sell_price","out_of_stock",
        "status"
    ];

    public $table = "items";

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
