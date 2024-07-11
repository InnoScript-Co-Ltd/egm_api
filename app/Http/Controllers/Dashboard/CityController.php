<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\CityStoreRequest;
use App\Http\Requests\CityUpdateRequest;
use App\Models\City;
use Exception;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $cities = City::with(['regionOrState'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('City list is successfully retrived', $cities);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function cityByRegionOrState($id)
    {
        DB::beginTransaction();
        try {

            $cities = City::where([
                "status" => "ACTIVE",
                "region_or_state_id" => $id
            ])->get();
            DB::commit();

            return $this->success('City list filter by region or state is successfully retrived', $cities);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(CityStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $city = City::create($payload->toArray());
            DB::commit();

            return $this->success('City is created successfully', $city);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $city = City::with(['regionOrState'])->findOrFail($id);
            DB::commit();

            return $this->success('City detail is successfully retrived', $city);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(CityUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $city = City::findOrFail($id);
            $city->update($payload->toArray());
            DB::commit();

            return $this->success('City is updated successfully', $city);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $city = City::findOrFail($id);
            $city->delete();
            DB::commit();

            return $this->success('City is deleted successfully', $city);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
