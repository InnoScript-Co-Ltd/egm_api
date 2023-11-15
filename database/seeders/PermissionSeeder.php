<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\PermissionEnum;
use App\Helpers\Enum;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect(Enum::make(PermissionEnum::class)->values())->map(function ($permission) {
            return [
                'name' => $permission,
                'guard_name' => 'api',
            ];
        });

        try {
            Permission::insert($permissions->toArray());
        } catch (Exception $e) {
            throw $e;
        }
    }
}
