<?php

namespace Database\Factories;

use App\Enums\AdminStatusEnum;
use App\Helpers\Enum;
use App\Helpers\Snowflake;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $adminStatus = (new Enum(AdminStatusEnum::class))->values();
        $status = $adminStatus[rand(0, count($adminStatus) - 1)];
        $verified_at = $status === 'PENDING' ? null : now();
        $snowflake = new Snowflake;

        return [
            'id' => $snowflake->next(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => $verified_at,
            'phone_verified_at' => $verified_at,
            'phone' => '9'.rand(100000000, 999999999),
            'status' => $status,
            'password' => static::$password ??= Hash::make('password'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'phone_verified_at' => null,
        ]);
    }
}
