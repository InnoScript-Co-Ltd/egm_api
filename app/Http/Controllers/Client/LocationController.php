<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\RegionOrState;
use Exception;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    protected $showalbeFields = [
        'country' => ['id', 'name', 'country_code', 'mobile_prefix'],
        'regionOrState' => ['id', 'name', 'country_id'],
        'city' => ['id', 'name', 'region_or_state_id'],
    ];

    private function cities($builder)
    {
        $builder->where(['status' => 'ACTIVE'])->select($this->showalbeFields['city']);
    }

    private function regionOrState($builder)
    {
        $builder->where(['status' => 'ACTIVE'])
            ->select($this->showalbeFields['regionOrState'])->with([
                'cities' => fn ($query) => $this->cities($query),
            ]);
    }

    private function country($builder)
    {
        $builder->where(['status' => 'ACTIVE'])
            ->with(['flagImage'])
            ->select($this->showalbeFields['country']);
    }

    private function cityIn($builder)
    {
        $builder->where(['status' => 'ACTIVE'])
            ->select(['id', 'name', 'country_id'])
            ->with([
                'country' => fn ($query) => $query->where(['status' => 'ACTIVE'])->with(['flagImage'])->select(['id', 'name', 'country_code', 'mobile_prefix']),
            ]);
    }

    public function countries()
    {
        DB::beginTransaction();

        try {
            $countries = Country::select($this->showalbeFields['country'])
                ->with(['flagImage', 'regionOrState' => fn ($query) => $this->regionOrState($query)])
                ->where(['status' => 'ACTIVE'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Countries list is successfully retrived', $countries);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function countryDetail($id)
    {
        DB::beginTransaction();
        try {

            $country = Country::findOrFail($id)
                ->where(['status' => 'ACTIVE'])
                ->with(['flagImage', 'regionOrState' => fn ($query) => $this->regionOrState($query)]);

            DB::commit();

            return $this->success('Country detail is successfully retrived', $country);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function regionOrStates()
    {
        DB::beginTransaction();

        try {
            $regionOrStates = RegionOrState::select($this->showalbeFields['regionOrState'])
                ->with(['cities' => fn ($query) => $this->cities($query)])
                ->where(['status' => 'ACTIVE'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Region or states list is successfully retrived', $regionOrStates);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function regionOrStateDetail($id)
    {
        DB::beginTransaction();
        try {

            $regionOrState = RegionOrState::findOrFail($id)
                ->where(['status' => 'ACTIVE'])
                ->select($this->showalbeFields['regionOrState'])
                ->with([
                    'cities' => fn ($query) => $this->cities($query),
                ])
                ->get();

            DB::commit();

            return $this->success('Region or state detail is successfully retrived', $regionOrState);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function cityIndex()
    {
        DB::beginTransaction();

        try {
            $cities = City::select($this->showalbeFields['city'])
                ->where(['status' => 'ACTIVE'])
                ->with(['regionOrState' => fn ($query) => $this->cityIn($query)])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Cities list is successfully retrived', $cities);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function cityDetail($id)
    {
        DB::beginTransaction();
        try {

            $city = City::findOrFail($id)
                ->where(['status' => 'ACTIVE'])
                ->select($this->showalbeFields['city'])
                ->with(['regionOrState' => fn ($query) => $this->cityIn($query)])
                ->get();

            DB::commit();

            return $this->success('City detail is successfully retrived', $city);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
