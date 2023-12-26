<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $fillable = [
        'category_id', 'shop_id', 'name', 'images', 'code', 'description', 'content', 'price', 'sell_price', 'out_of_stock', 'instock',
        'status',
    ];

    public $table = 'items';

    public $appends = ['category_name', 'shop_name'];

    protected $casts = [
        'images' => 'json',
        'out_of_stock' => 'boolean',
    ];

    protected function getCategoryNameAttribute()
    {
        $category = Category::where(['id' => $this->attributes['category_id']])->first();
        if ($category) {
            return $category->title;
        } else {
            return null;
        }
    }

    protected function getShopNameAttribute()
    {
        $shop = Shop::where(['id' => $this->attributes['shop_id']])->first();
        if ($shop) {
            return $shop->name;
        } else {
            return null;
        }
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
