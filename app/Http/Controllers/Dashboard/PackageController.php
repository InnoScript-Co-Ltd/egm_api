<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\PackageStoreRequest;
use App\Http\Requests\PackageUpdateRequest;
use App\Models\Package;
use Exception;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $packages = Package::searchQuery()
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

    public function store(PackageStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $package = Package::create($payload->toArray());
            DB::commit();

            return $this->success('New Package is created successfully', $package);

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

    public function update(PackageUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $package = Package::findOrFail($id);
            $package->update($payload->toArray());
            DB::commit();

            return $this->success('package is updated successfully', $package);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $package = Package::findOrFail($id);
            $package->delete();
            DB::commit();

            return $this->success('Package is deleted successfully', $package);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
