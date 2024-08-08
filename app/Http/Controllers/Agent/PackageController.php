<?php

namespace App\Http\Controllers\Agent;

use App\Enums\GeneralStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Models\Package;
use Exception;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {
            $packages = Package::where(['status' => GeneralStatusEnum::ACTIVE->value])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('packages list is successfully retrived', $packages);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $package = Package::findOrFail($id);
            DB::commit();

            return $this->success('Package detail is successfully retrived', $package);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
