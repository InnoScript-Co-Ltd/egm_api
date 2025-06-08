<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralPartner extends Model
{
    use HasFactory;

    protected $table = 'referral_partners';

    protected $fillable = [
        'partner_id',
        'referral_id',
    ];

    public function partners()
    {
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }
}
