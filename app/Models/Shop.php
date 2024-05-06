<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    public $table = 'shops';

    protected $fillable = [
        'country_id', 'region_or_state_id', 'city_id', 'township_id', 'name', 'phone',
        'email', 'address', 'description', 'location', 'app_type', 'status',
    ];

    public function country(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function regionOrState(): HasOne
    {
        return $this->hasOne(RegionOrState::class, 'id', 'region_or_state_id');
    }

    public function city(): HasOne
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function township(): HasOne
    {
        return $this->hasOne(Township::class, 'id', 'township_id');
    }

    public function coverPhoto()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', 'cover_photo');
    }

    public function shopLogo()
    {
        return $this->morphOne(Image::class, 'imageable')->where('type', 'shop_logo');
    }
}
