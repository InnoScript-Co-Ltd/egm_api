<?php

namespace Database\Factories;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $region = Region::all()->pluck('id');
        $regionId = $region[rand(0, count($region) - 1)];

        $status = (new Enum(GeneralStatusEnum::class))->values();
        $activeStatus = $status[rand(0, count($status) - 1)];

        return [
            'region_id' => $regionId,
            'name' => fake()->name(),
            'phone' => fake()->name(),
            'address' => fake()->name(),
            'location' => fake()->name(),
            'status' => $activeStatus,
        ];
    }
}
