<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            // PointSeeder::class,
            RoleSeeder::class,
            // UserSeeder::class,
            // DeliveryAddressSeeder::class,
            // OrderSeeder::class,
            // AdminSeeder::class,
            // PromotionSeeder::class,
            // FaqSeeder::class,
            // RegionSeeder::class,
            // ShopSeeder::class,
            // CategorySeeder::class,
            // ItemSeeder::class,
            // MemberDiscountSeeder::class,
            // MemberCardSeeder::class,
            // FaqSeeder::class,
            // MemberSeeder::class,
            // MemberOrderSeeder::class,
        ]);
    }
}
