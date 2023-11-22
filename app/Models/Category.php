<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $fillable = [
        'title', 'level', 'category_id', 'description', 'status',
    ];

    public $table = 'categories';

    public $appends = ['category_name'];

    protected function getCategoryNameAttribute()
    {
        if($this->category_id !== null)
        {
            if ($this->attributes['category_id'] !== null)
            {
                $category = Category::where(['id' => $this->attributes['category_id']])->first();
                if ($category) {
                    return $category->title;
                } else {
                    return null;
                }
            }
        }
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'category_id');
    }
}
