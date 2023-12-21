<?php

namespace Database\Factories;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::all()->pluck('id');
        $categoryId = $category[rand(0, count($category) - 1)];

        $shop = Shop::all()->pluck('id');
        $shopId = $shop[rand(0, count($shop) - 1)];

        $status = (new Enum(GeneralStatusEnum::class))->values();
        $activeStatus = $status[rand(0, count($status) - 1)];

        return [
            'category_id' => $categoryId,
            'shop_id' => $shopId,
            'name' => fake()->name(),
            // "image" => [
            //     "id" => fake()->randomNumber(),
            //     "is_feature" => false
            // ],
            'code' => fake()->name(),
            'sell_price' => fake()->randomNumber(4),
            'out_of_stock' => false,
            'status' => $activeStatus,
        ];
    }
}
