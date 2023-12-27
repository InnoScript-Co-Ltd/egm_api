<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            PointSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            DeliveryAddressSeeder::class,
            OrderSeeder::class,
            AdminSeeder::class,
            PromotionSeeder::class,
            FaqSeeder::class,
            RegionSeeder::class,
            ShopSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
        ]);
    }
}
