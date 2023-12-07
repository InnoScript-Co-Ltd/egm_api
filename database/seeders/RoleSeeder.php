<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Helpers\Enum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = Enum::make(PermissionEnum::class)->values();

        $roles = collect(Enum::make(RoleEnum::class)->values())->map(function ($role, $key) use($permissions) {
            try {
                $createRole = Role::create([
                    'name' => $role,
                    'guard_name' => 'api',
                ]);

                if($createRole->name === RoleEnum::SUPER_ADMIN->value)
                {
                    $createRole->syncPermissions($permissions);
                }

            } catch (Exception $e) {
                info($e);
            }
        });
    }
}
