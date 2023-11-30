<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    protected static ?string $password;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@gscexport.com',
            'phone' => '9421038123',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'status' => 'ACTIVE',
            'password' => static::$password ??= Hash::make('password'),
        ]);

        \App\Models\Admin::factory(1000)->create();
    }
}
