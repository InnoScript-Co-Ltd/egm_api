<?php

namespace App\Models;

use App\Traits\SnowflakeID;
use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory,SnowflakeID,BasicAudit,SoftDeletes;

    protected $fillable = [
        "name","profile","email","phone","password", 'status', 'email_verified_at', 'phone_verified_at',
    ];

    public $table = "admins";

}
