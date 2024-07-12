<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\GeneralStatusEnum;
use App\Http\Requests\RegionAndStateStoreRequest;
use App\Http\Requests\RegionAndStateUpdateRequest;
use App\Models\RegionOrState;
use Exception;
use Illuminate\Support\Facades\DB;

class RegionAndStateController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $regionsAndStates = RegionOrState::with(['country', 'cities'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Region or state list is successfully retrived', $regionsAndStates);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function countryBy($id)
    {
        DB::beginTransaction();

        try {
            $regionsAndStates = RegionOrState::where([
                'status' => GeneralStatusEnum::ACTIVE->value,
                'country_id' => $id,
            ])->get();

            DB::commit();

            return $this->success('Region or states filter by country is successfully retrived', $regionsAndStates);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(RegionAndStateStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $regionsAndStates = RegionOrState::create($payload->toArray());
            DB::commit();

            return $this->success('Region or state is created successfully', $regionsAndStates);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $regionsAndStates = RegionOrState::with(['country'])->findOrFail($id);
            DB::commit();

            return $this->success('Region or state detail is successfully retrived', $regionsAndStates);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(RegionAndStateUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $regionOrState = RegionOrState::findOrFail($id);
            $regionOrState->update($payload->toArray());
            DB::commit();

            return $this->success('Region or state is updated successfully', $regionOrState);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $regionOrState = RegionOrState::findOrFail($id);
            $regionOrState->delete();
            DB::commit();

            return $this->success('Region or state is deleted successfully', $regionOrState);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
