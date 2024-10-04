<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use BasicAudit, HasFactory, SnowflakeID, SoftDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'sender_id',
        'sender_account_id',
        'merchant_account_id',
        'package_id',
        'sender_name',
        'sender_email',
        'sender_phone',
        'sender_nrc',
        'sender_address',
        'sender_account_name',
        'sender_account_number',
        'sender_bank_branch',
        'sender_bank_address',
        'merchant_account_name',
        'merchant_account_number',
        'bank_type',
        'package_name',
        'package_roi_rate',
        'package_duration',
        'package_deposit_amount',
        'transaction_screenshoot',
        'transaction_type',
        'sender_type',
        'status',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];
}
