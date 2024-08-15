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
        'agent_id', 'bank_account_id', 'merchant_account_id', 'package_id',
        'agent_name', 'agent_email', 'agent_phone', 'agent_nrc', 'agent_address', 'agent_account_name', 'agent_account_number', 'agent_bank_branch', 'agent_bank_address',
        'merchant_account_name', 'merchant_account_number', 'bank_type',
        'package_name', 'package_roi_rate', 'package_duration', 'package_deposit_amount',
        'transaction_screenshoot',
        'status',
    ];
}
