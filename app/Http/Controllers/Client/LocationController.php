<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\RegionOrState;
use App\Models\Township;
use Exception;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    protected $showalbeFields = [
        'country' => ['id', 'name', 'country_code', 'mobile_prefix'],
        'regionOrState' => ['id', 'name', 'country_id'],
        'city' => ['id', 'name', 'region_or_state_id'],
        'township' => ['id', 'name', 'city_id'],
    ];

    protected $active = [
        'status' => 'ACTIVE',
    ];

    private function country($builder)
    {
        $builder->where($this->active)
            ->with(['flagImage'])
            ->select($this->showalbeFields['country']);
    }

    private function regionOrState($builder)
    {
        $builder->where($this->active)
            ->select($this->showalbeFields['regionOrState'])->with([
                'cities' => fn ($query) => $this->cities($query),
            ]);
    }

    private function cities($builder)
    {
        $builder->where($this->active)
            ->select($this->showalbeFields['city'])
            ->with([
                'townships' => fn ($query) => $this->townships($query),
            ]);
    }

    private function townships($builder)
    {
        $builder->where($this->active)->select($this->showalbeFields['township']);
    }

    private function cityIn($builder)
    {
        $builder->where($this->active)
            ->select($this->showalbeFields['city'])
            ->with([
                'regionOrState' => fn ($query) => $query->select($this->showalbeFields['regionOrState'])
                    ->where($this->active)
                    ->with([
                        'country' => fn ($query) => $query->select($this->showalbeFields['country'])
                            ->where($this->active),
                    ]),
            ]);
    }

    public function countries()
    {
        DB::beginTransaction();

        try {
            $countries = Country::select($this->showalbeFields['country'])
                ->with(['flagImage', 'regionOrState' => fn ($query) => $this->regionOrState($query)])
                ->where($this->active)
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
                ->where($this->active)
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
                ->where($this->active)
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
                ->where($this->active)
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
                ->where($this->active)
                ->with(['townships' => fn ($query) => $this->townships($query)])
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
                ->where($this->active)
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

    public function townshipIndex()
    {
        DB::beginTransaction();

        try {
            $townships = Township::select($this->showalbeFields['township'])
                ->where($this->active)
                ->with(['city' => fn ($query) => $this->cityIn($query)])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Township list is successfully retrived', $townships);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function townshipDetail($id)
    {
        DB::beginTransaction();
        try {

            $township = Township::select($this->showalbeFields['township'])
                ->where($this->active)
                ->with(['city' => fn ($query) => $this->cityIn($query)])
                ->get();

            DB::commit();

            return $this->success('township detail is successfully retrived', $township);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
