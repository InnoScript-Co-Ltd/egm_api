<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use BasicAudit, HasFactory, SnowflakeID, SoftDeletes;

    protected $table = 'devices';

    protected $fillable = [
        'user_type', 'user_id', 'men_used', 'disk_free', 'free_disk_total', 'real_disk_free', 'real_disk_total',
        'model', 'operation_system', 'os_version', 'android_sdk_version', 'platform', 'manufacture', 'brand_name',
        'web_version', 'deviced_id', 'device_language',
    ];
}
