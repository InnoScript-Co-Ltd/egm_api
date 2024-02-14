<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MemberOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\MemberOrder::factory(100)->create();
    }
}
