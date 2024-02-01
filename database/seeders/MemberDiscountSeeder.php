<?php

namespace Database\Seeders;

use App\Models\MemberDiscount;
use Illuminate\Database\Seeder;

class MemberDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discountPlans = [
            [
                'label' => 'Normal Membership',
                'discount_percentage' => 5,
                'discount_fix_amount' => 0,
                'expend_limit' => 100000.00,
                'is_expend_limit' => true,
                'is_fix_amount' => false,
                'status' => 'ACTIVE',
            ],
            [
                'label' => 'Gold Membership',
                'discount_percentage' => 10,
                'discount_fix_amount' => 0,
                'expend_limit' => 300000.00,
                'is_expend_limit' => true,
                'is_fix_amount' => false,
                'status' => 'ACTIVE',
            ],
            [
                'label' => 'VIP Membership',
                'discount_percentage' => 20,
                'discount_fix_amount' => 10000.00,
                'expend_limit' => 1000000.00,
                'is_expend_limit' => true,
                'is_fix_amount' => true,
                'start_date' => null,
                'end_date' => null,
                'status' => 'ACTIVE',
            ],
        ];

        foreach ($discountPlans as $discountPlan) {
            MemberDiscount::create($discountPlan);
        }
    }
}
