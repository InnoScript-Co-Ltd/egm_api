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
        'title', 'level', 'icon', 'main_category_id', 'description', 'status',
    ];

    public $table = 'categories';

    public $appends = ['created_by', 'updated_by', 'main_category_name'];

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'main_category_id');
    }

    protected function getMainCategoryNameAttribute()
    {
        if ($this->main_category_id !== null) {
            if ($this->attributes['main_category_id'] !== null) {
                $category = Category::where(['id' => $this->attributes['main_category_id']])->first();
                if ($category) {
                    return $category->title;
                } else {
                    return null;
                }
            }
        }
    }

    protected function getCreatedByAttribute()
    {
        if (auth('dashboard')->id()) {
            $user = Admin::where(['id' => $this->attributes['created_by']])->first();
        }

        if (auth('api')->id()) {
            $user = User::where(['id' => $this->attributes['created_by']])->first();
        }

        if ($user) {
            return ['name' => $user->name, 'id' => $user->id];
        } else {
            return null;
        }
    }

    protected function getUpdatedByAttribute()
    {
        if (auth('dashboard')->id()) {
            $user = Admin::where(['id' => $this->attributes['updated_by']])->first();
        }

        if (auth('api')->id()) {
            $user = User::where(['id' => $this->attributes['updated_by']])->first();
        }

        if ($user) {
            return ['name' => $user->name, 'id' => $user->id];
        } else {
            return null;
        }
    }
}
