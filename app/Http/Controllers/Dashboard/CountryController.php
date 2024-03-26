<?php

namespace App\Http\Controllers\Dashboard;

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

            $countries = Country::with(['flagImage', 'regionOrState'])
                ->searchQuery()
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

            if (isset($payload['flag_image'])) {
                $imagePath = $payload['flag_image']->store('images', 'public');
                $flagImage = explode('/', $imagePath)[1];
                $country->flagImage()->updateOrCreate(['imageable_id' => $country->id], [
                    'image' => $flagImage,
                    'imageable_id' => $country->id,
                    'image_type' => 'COUNTRY_FLAG_IMAGE',
                ]);

                $country['flag_image'] = $flagImage;
            }
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

            $country = Country::with(['flagImage', 'regionOrState'])->findOrFail($id);
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

            if (isset($payload['flag_image'])) {
                $imagePath = $payload['flag_image']->store('images', 'public');
                $flagImage = explode('/', $imagePath)[1];
                $country->flagImage()->updateOrCreate(['imageable_id' => $country->id], [
                    'image' => $flagImage,
                    'imageable_id' => $country->id,
                    'image_type' => 'COUNTRY_FLAG_IMAGE',
                ]);

                $country['flag_image'] = $flagImage;
            }
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
