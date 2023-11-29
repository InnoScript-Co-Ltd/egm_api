<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    public $table = 'regions';

    protected $fillable = [
        'name', 'status',
    ];

    public function shop()
    {
        return $this->hasMany(Shop::class, 'id', 'region_id');
    }
}
