<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Township extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $guard = 'dashboard';

    protected $table = 'townships';

    protected $fillable = [
        'name', 'city_id', 'status',
    ];

    public $appends = ['city_name'];

    protected function getCityNameAttribute()
    {
        $city = City::where(['id' => $this->attributes['city_id']])->first();
        if ($city) {
            return $city->name;
        } else {
            return null;
        }
    }

    public function city(): HasOne
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
}
