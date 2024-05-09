<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Shop;
use Exception;
use Illuminate\Support\Facades\DB;

class ClientShopController extends Controller
{
    protected $showalbeFields = [
        'shop' => ['name', 'country_id', 'region_or_state_id', 'city_id', 'township_id', 'phone', 'email', 'address', 'decription', 'location', 'app_type'],
        'country' => ['id', 'name', 'country_code', 'mobile_prefix'],
        'regionOrState' => ['id', 'name'],
        'city' => ['id', 'name'],
        'township' => ['id', 'name'],
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

    public function index()
    {
        DB::beginTransaction();

        try {
            $shop = Shop::where($this->active)
                ->with([
                    'shopLogo', 'coverPhoto',
                    'country' => fn ($query) => $query->where($this->active)->with(['flagImage'])->select($this->showalbeFields['country']),
                    'regionOrState' => fn ($query) => $query->where($this->active)->select($this->showalbeFields['regionOrState']),
                    'city' => fn ($query) => $query->where($this->active)->select($this->showalbeFields['city']),
                    'township' => fn ($query) => $query->where($this->active)->select($this->showalbeFields['township']),
                ])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Shop list is successfully retrived', $shop);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {
            $shop = Shop::findOrFail($id)
                ->where($this->active)
                ->with([
                    'shopLogo', 'coverPhoto',
                    'country' => fn ($query) => $query->where($this->active)->with(['flagImage'])->select($this->showalbeFields['country']),
                    'regionOrState' => fn ($query) => $query->where($this->active)->select($this->showalbeFields['regionOrState']),
                    'city' => fn ($query) => $query->where($this->active)->select($this->showalbeFields['city']),
                    'township' => fn ($query) => $query->where($this->active)->select($this->showalbeFields['township']),
                ])
                ->get();
            DB::commit();

            return $this->success('Shop detail is successfully retrived', $shop[0]);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
