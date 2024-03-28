<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegionOrState extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $guard = 'dashboard';

    protected $table = 'regions_and_states';

    protected $fillable = [
        'name', 'country_id', 'status',
    ];

    public $appends = ['country_name'];

    protected function getCountryNameAttribute()
    {
        $country = Country::where(['id' => $this->attributes['country_id']])->first();
        if ($country) {
            return $country->name;
        } else {
            return null;
        }
    }

    public function country(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'region_or_state_id', 'id');
    }
}
