<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Helpers\Enum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = collect(Enum::make(RoleEnum::class)->values())->map(function ($role, $key) {
            try {
                $createRole = Role::create([
                    'name' => $role,
                    'guard_name' => 'dashboard',
                ]);

                if ($createRole->name === RoleEnum::SUPER_ADMIN->value) {
                    $createRole->syncPermissions(Permission::all());
                }

                if ($createRole->name === RoleEnum::GENERAL_MANAGER->value) {
                    $createRole->syncPermissions([2,3,4]);
                }

            } catch (Exception $e) {
                throw ($e);
            }
        });
    }
}
