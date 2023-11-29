<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    public $table = "faq";

    protected $fillable = [
        "answer", "question", "status"
    ];
}
