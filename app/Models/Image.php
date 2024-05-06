<?php

namespace App\Models;

use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SnowflakeID, SoftDeletes;

    protected $table = 'images';

    protected $fillable = ['image', 'type', 'imageable_type', 'imageable_id', 'status'];

    protected $hidden = [
        'imageable_type', 'imageable_id', 'type', 'status', 'created_at', 'updated_at', 'deleted_at',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
