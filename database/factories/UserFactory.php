<?php

namespace Database\Factories;

use App\Enums\PointLabelEnum;
use App\Enums\UserStatusEnum;
use App\Helpers\Enum;
use App\Models\Point;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $point = collect(Point::where(['label' => PointLabelEnum::LOGIN_POINT->value])->first());
        $status = (new Enum(UserStatusEnum::class))->values();
        $activeStatus = $status[rand(0, count($status) - 1)];

        return [
            'name' => fake()->name(),
            'profile' => null,
            'reward_point' => $point ? $point['point'] : 0,
            'coupons' => null,
            'cart_items' => null,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'email_verified_at' => $activeStatus === 'ACTIVE' ? now() : null,
            'phone_verified_at' => $activeStatus === 'ACTIVE' ? now() : null,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'status' => $activeStatus,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
