<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
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
            "title" => fake()->name(),
            "level" => 0,
            "status" => $activeStatus
        ];
    }
}
