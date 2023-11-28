<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\DeliveryAddress;
use App\Helpers\Enum;
use App\Enums\OrderStatusEnum;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all()->pluck("id");
        $userId = $user[rand(0, count($user) - 1)];

        $deliveryAddress = DeliveryAddress::all()->pluck("id");
        $daId = $deliveryAddress[rand(0, count($deliveryAddress) -1)];

        $status = (new Enum(OrderStatusEnum::class))->values();
        $activeStatus = $status[rand(0, count($status) - 1)];

        return [
            "delivery_address_id" => $daId,
            "user_id" => $userId,
            "user_name" => fake()->name(),
            "phone" => fake()->name(),
            "email" => fake()->email(),
            "delivery_address" => fake()->name(),
            "delivery_contact_person" => fake()->name(),
            "delivery_contact_phone" => fake()->name(),
            "discount" => fake()->randomNumber(5),
            "delivery_feed" => fake()->randomNumber(5),
            "total_amount" => fake()->randomNumber(5),
            "payment_type" => fake()->name(),
            "status" => $activeStatus
        ];
    }
}
