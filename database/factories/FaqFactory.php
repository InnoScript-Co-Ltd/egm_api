<?php

namespace Database\Factories;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
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
            'answer' => fake()->name(),
            'question' => fake()->name(),
            'status' => $activeStatus,
        ];
    }
}
