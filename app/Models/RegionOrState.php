<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegionOrState extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $guard = 'dashboard';

    protected $table = 'regions_and_states';

    protected $fillable = [
        'name', 'country_id', 'status',
    ];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
