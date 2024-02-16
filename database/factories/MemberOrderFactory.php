<?php

namespace Database\Factories;

use App\Enums\MembershipOrderStatusEnum;
use App\Helpers\Enum;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberOrder>
 */
class MemberOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $MembershipOrderStatus = (new Enum(MembershipOrderStatusEnum::class))->values();
        $status = $MembershipOrderStatus[rand(0, count($MembershipOrderStatus) - 1)];

        $user = User::all()->pluck('id');
        $userId = $user[rand(0, count($user) - 1)];

        $member = Member::all()->pluck('id');
        $memberId = $member[rand(0, count($member) - 1)];

        return [
            'name' => fake()->name(),
            'phone' => '9'.rand(100000000, 999999999),
            'user_id' => $userId,
            'member_id' => $memberId,
            'order_number' => 'kmdkasdasmd',
            'card_type' => 'VIP',
            'card_number' => 'skmdkmasdm',
            'amount' => rand(1000000, 9999999),
            'discount' => rand(10000, 50000),
            'pay_amount' => rand(200000, 9999999),
            'is_wallet' => false,
            'email' => fake()->unique()->safeEmail(),
            'status' => $status,
        ];
    }
}
