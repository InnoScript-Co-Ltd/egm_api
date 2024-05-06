<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ExportShop;
use App\Http\Requests\ShopStoreRequest;
use App\Http\Requests\ShopUpdateRequest;
use App\Models\Shop;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ShopController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $shop = Shop::with([
                'shopLogo', 'coverPhoto', 'country', 'regionOrState', 'city', 'township',
            ])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Shop list is successfully retrived', $shop);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(ShopStoreRequest $request)
    {

        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $shop = Shop::create($payload->toArray());

            $imagePath = $payload['cover_photo']->store('images', 'public');
            $coverPhoto = explode('/', $imagePath)[1];
            $shop->coverPhoto()->create([
                'image' => $coverPhoto,
                'type' => 'cover_photo'
            ]);

            $imagePath = $payload['shop_logo']->store('images', 'public');
            $shopLogo = explode('/', $imagePath)[1];
            $shop->shopLogo()->create([
                'image' => $shopLogo,
                'type' => 'shop_logo'
            ]);

            $shop['cover_photo'] = $coverPhoto;
            $shop['shop_logo'] = $shopLogo;

            DB::commit();

            return $this->success('Shop is created successfully', $shop);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $shop = Shop::with([
                'shopLogo', 'coverPhoto',
                'country' => fn ($query) => $query->select(['name', 'country_code', 'mobile_prefix']),
                'regionOrState' => fn ($query) => $query->select(['country_id', 'name']),
                'city' => fn ($query) => $query->select(['region_or_state_id', 'name']),
                'township' => fn ($query) => $query->select(['city_id', 'name']),
            ])
                ->findOrFail($id);
            DB::commit();

            return $this->success('Shop detail is successfully retrived', $shop);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(ShopUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $shop = Shop::findOrFail($id);
            if ($request->hasFile('cover_photo')) {
                $imagePath = $payload['cover_photo']->store('images', 'public');
                $coverPhoto = explode('/', $imagePath)[1];
                $shop->coverPhoto()->where('imageable_id', '=', $shop->id)->delete();
                $shop->coverPhoto()->updateOrCreate(['imageable_id' => $shop->id], [
                    'image' => $coverPhoto,
                    'type' => 'cover_photo'
                ]);

                $payload['cover_photo'] = $coverPhoto;
            }

            if ($request->hasFile('shop_logo')) {
                $imagePath = $payload['shop_logo']->store('images', 'public');
                $shopLogo = explode('/', $imagePath)[1];
                $shop->shopLogo()->where('imageable_id', '=', $shop->id)->delete();
                $shop->shopLogo()->updateOrCreate(['imageable_id' => $shop->id], [
                    'image' => $shopLogo,
                    'type' => 'shop_logo'
                ]);

                $payload['shop_logo'] = $shopLogo;
            }

            $shop->update($payload->toArray());
            DB::commit();

            return $this->success('Shop is updated successfully', $shop);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $shop = Shop::findOrFail($id);
            $shop->delete();
            DB::commit();

            return $this->success('Shop is deleted successfully', $shop);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function export()
    {
        return Excel::download(new ExportShop, 'Shops.xlsx');
    }
}
