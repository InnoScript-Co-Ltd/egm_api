<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Category;
use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $fillable = [
        'category_id', 'shop_id', 'name', 'thumbnail_photo', 'product_photo', 'item_code', 
        'item_color', 'item_size', 'description', 'content', 'price', 'sell_price', 'instock',
        'status',
    ];

    public $table = 'items';

    public $appends = ['category_name', 'shop_name'];

    protected $casts = [
        'product_photo' => 'json',
        "item_color" => 'json',
        "item_size" => 'json',
        'out_of_stock' => 'boolean',
    ];

    protected function getCategoryNameAttribute()
    {
        $category = Category::where(['id' => $this->attributes['category_id']])->first();
        if ($category) {
            return $category->name;
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

    public function thumbnailPhoto()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function productPhoto()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

}
