<?php

namespace App\Models;

use App\Traits\SnowflakeID;
use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Category extends Model
{
    use HasFactory,SnowflakeID,BasicAudit,SoftDeletes;

    protected $fillable = [
        "title","level","category_id","description","status"
    ];

    public $table = "categories";

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'category_id');
    }

}
