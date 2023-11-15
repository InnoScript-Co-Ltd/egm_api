<?php

namespace App\Models;

use App\Traits\SnowflakeID;
use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory,SnowflakeID,BasicAudit,SoftDeletes;

    protected $fillable = [
        "title", "image", "url", "status"
    ];

    public $table = "promotions";
}
