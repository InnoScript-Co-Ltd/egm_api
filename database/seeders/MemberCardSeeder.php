<?php

namespace Database\Seeders;

use App\Models\MemberCard;
use App\Models\MemberDiscount;
use Illuminate\Database\Seeder;

class MemberCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activeDiscountPlans = MemberDiscount::where(['status' => 'ACTIVE'])->get()->toArray();

        $memberCard = [
            'label' => 'Normal Membership',
            'discount_id' => $activeDiscountPlans[0]['id'],
            'front_background' => null,
            'back_background' => null,
            'expired_at' => null,
            'status' => 'ACTIVE',
        ];

        MemberCard::create($memberCard);
    }
}
