<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Point extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'points';

    protected $fillable = ['label', 'point'];

    protected $appends = ['created_by', 'updated_by'];

    protected function getCreatedByAttribute()
    {
        $admin = Admin::where(['id' => $this->attributes['created_by']])->first();

        if ($admin) {
            return ['name' => $admin->name, 'id' => $admin->id];
        } else {
            return null;
        }
    }

    protected function getUpdatedByAttribute()
    {
        $admin = Admin::where(['id' => $this->attributes['created_by']])->first();

        if ($admin) {
            return ['name' => $admin->name, 'id' => $admin->id];
        } else {
            return null;
        }
    }
}
