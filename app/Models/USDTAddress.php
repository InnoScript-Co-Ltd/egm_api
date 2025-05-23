<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class USDTAddress extends Model
{
    use BasicAudit, HasFactory, SnowflakeID, SoftDeletes;

    protected $table = 'usdt_address';

    protected $fillable = [
        'partner_id', 'email', 'phone', 'name', 'address', 'address_type', 'status',
    ];

    public function partner()
    {
        return $this->hasOne(Partner::class, 'id', 'partner_id');
    }
}
