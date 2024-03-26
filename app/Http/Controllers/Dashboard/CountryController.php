<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\CountryStoreRequest;
use App\Http\Requests\CountryUpdateRequest;
use App\Models\Country;
use Exception;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $countries = Country::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Country list is successfully retrived', $countries);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(CountryStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $country = Country::create($payload->toArray());
            DB::commit();

            return $this->success('Country is created successfully', $country);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $country = Country::findOrFail($id);
            DB::commit();

            return $this->success('Country detail is successfully retrived', $country);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(CountryUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $country = Country::findOrFail($id);
            $country->update($payload->toArray());
            DB::commit();

            return $this->success('Country is updated successfully', $country);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $country = Country::findOrFail($id);
            $country->delete();
            DB::commit();

            return $this->success('Country is deleted successfully', $country);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
