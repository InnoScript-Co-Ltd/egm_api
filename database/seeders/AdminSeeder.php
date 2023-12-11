<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Helpers\Enum;
use App\Enums\RoleEnum;

class AdminSeeder extends Seeder
{
    protected static ?string $password;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $superAdmin = [
            'name' => 'Administrator',
            'email' => 'admin@gscexport.com',
            'phone' => '9421038123',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'status' => 'ACTIVE',
            'password' => static::$password ??= Hash::make('password'),
        ];

        $roles = Enum::make(RoleEnum::class)->values();

        try {
            $admin = Admin::updateOrCreate($superAdmin)->assignRole($roles);
            Admin::factory(100)->create();
            info($admin);
        } catch (Exception $e) {
            info($e);
        } 
    }
}
