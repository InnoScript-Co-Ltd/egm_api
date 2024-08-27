<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    protected static ?string $password;

    public function run(): void
    {
        $superAdmin = [
            'name' => 'Administrator',
            'email' => 'admin@evanglobalmanagement.com',
            'phone' => '9421038123',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'status' => 'ACTIVE',
            'password' => static::$password ??= Hash::make('password'),
        ];

        try {
            Admin::updateOrCreate($superAdmin)->assignRole('SUPER_ADMIN');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
