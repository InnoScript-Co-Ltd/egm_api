<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    public $table = 'promotions';

    protected $fillable = [
        'title', 'app_type', 'start_date', 'end_date', 'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', 'image');
    }

    public function items()
    {
        return $this->hasMany(PromotionInItem::class, 'promotion_id', 'id');
    }
}
