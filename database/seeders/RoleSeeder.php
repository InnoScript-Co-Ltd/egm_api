<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Helpers\Enum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

            } catch (Exception $e) {
                info($e);
            }
        });
    }
}
