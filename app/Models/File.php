<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use BasicAudit, HasFactory, SnowflakeID, SoftDeletes;

    protected $table = 'files';

    protected $fillable = [
        'name', 'category', 'size', 'type', 'status',
    ];
}
