<?php

namespace Database\Factories;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = (new Enum(GeneralStatusEnum::class))->values();
        $activeStatus = $status[rand(0, count($status) - 1)];

        return [
            'title' => fake()->name(),
            'image' => fake()->name(),
            'url' => fake()->name(),
            'status' => $activeStatus,
        ];
    }
}
