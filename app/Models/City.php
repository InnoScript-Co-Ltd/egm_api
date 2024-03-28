<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $guard = 'dashboard';

    protected $table = 'cities';

    protected $fillable = [
        'name', 'region_or_state_id', 'status',
    ];

    public function regionOrState(): HasOne
    {
        return $this->hasOne(RegionOrState::class, 'id', 'region_or_state_id');
    }

    public function townships(): HasMany
    {
        return $this->hasMany(Township::class, 'city_id', 'id');
    }
}
