<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeliveryAddress>
 */
class DeliveryAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all()->pluck('id');
        $userId = $user[rand(0, count($user) - 1)];

        return [
            'user_id' => $userId,
            'address' => fake()->text(),
            'contact_phone' => fake()->name(),
            'contact_person' => fake()->name(),
            'is_default' => false,
        ];
    }
}
