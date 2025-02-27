<?php

namespace App\Models;

use App\Enums\GeneralStatusEnum;
use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradeRecord extends Model
{
    use HasFactory,BasicAudit,SnowflakeID,SoftDeletes;
    protected $table = 'trade_records';


    protected $fillable = [
        'title',
        'photos',
        'description',
        'content',
        'status',

    ];
    protected $casts = [
        'photos' => 'array',
    ];
    public function scopeActive()
    {
        return $this->where('status', GeneralStatusEnum::ACTIVE->value);
    }
}


