<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'history';

    protected $fillable = [
        'partner_id', 'repayment_id', 'withdraw_id', 'transaction_id', 'type', 'repayment_amount', 'deposit_amount', 'withdraw_amount', 'title', 'description', 'status',
    ];
}
