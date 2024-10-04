<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repayment extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'repayments';

    protected $fillable = [
        'deposit_id', 'transaction_id', 'agent_id', 'partner_id', 'date', 'amount', 'total_amount', 'total_days', 'count_days', 'oneday_amount', 'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
