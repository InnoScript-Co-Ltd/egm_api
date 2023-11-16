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

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'category_id');
    }
}
