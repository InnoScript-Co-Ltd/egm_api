<?php

namespace Database\Seeders;

use App\Enums\PointLabelEnum;
use App\Helpers\Enum;
use App\Models\Point;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $points = collect(Enum::make(PointLabelEnum::class)->values())->map(function ($point) {
            DB::beginTransaction();
            try {
                Point::create([
                    'label' => $point,
                    'point' => 1000,
                ]);
                DB::commit();

            } catch (Exception $e) {
                throw $e;
                DB::rollback();
            }
        });
    }
}
