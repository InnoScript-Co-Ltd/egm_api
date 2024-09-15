<?php

namespace App\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerBankAccount extends Model
{
    use BasicAudit,HasFactory,SnowflakeID,SoftDeletes;

    protected $table = 'partner_bank_accounts';

    protected $fillable = [
        'partner_id', 'account_name', 'account_number', 'bank_type', 'bank_type_label', 'branch', 'branch_address', 'status',
    ];

    public function deposit()
    {
        return $this->hasMany(Deposit::class, 'partner_id', 'id');
    }
}
