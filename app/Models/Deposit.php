<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'deposits';

    protected $fillable = [
        'agent_id',
        'agent_name', 'agent_email', 'agent_phone', 'agent_nrc', 'agent_address',
        'package_id',
        'name', 'roi_rate', 'duration', 'deposit_amount',
        'bank_account_id',
        'account_name', 'account_number', 'bank_type', 'branch', 'branch_address',
        'merchant_account',
        'transaction_screenshoot',
        'status',
    ];
}
