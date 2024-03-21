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

    public $table = 'categories';

    protected $fillable = [
        'title', 'description', 'app_type', 'status'
    ];

    public function icon()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

}
