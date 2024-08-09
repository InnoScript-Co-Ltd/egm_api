<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'bank_accounts';

    protected $fillable = [
        'agent_id', 'account_name', 'account_number', 'bank_type', 'branch', 'branch_address',
    ];
}
