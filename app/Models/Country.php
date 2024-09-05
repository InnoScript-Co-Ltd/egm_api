<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $guard = 'dashboard';

    protected $table = 'countries';

    protected $fillable = [
        'name', 'flag', 'country_code', 'mobile_prefix', 'status',
    ];

    protected $casts = [
    ];

    public function regionOrState(): HasMany
    {
        return $this->hasMany(RegionOrState::class, 'country_id', 'id');
    }
}
