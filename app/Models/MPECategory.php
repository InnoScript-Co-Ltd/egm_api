<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MPECategory extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $connection;

    public function __construct()
    {
        $this->connection = env('MPE_DATABASE');
    }

    protected $table = 'categories';

    protected $fillable = ['name', 'status'];
}
