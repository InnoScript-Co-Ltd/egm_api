<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopStoreRequest;
use App\Http\Requests\ShopUpdateRequest;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $shop = Shop::with('region')
                ->searchQuery()
                ->sortingQuery()
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

            $shop = Shop::findOrFail($id);
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
            $shop->delete($id);
            DB::commit();

            return $this->success('Shop is deleted successfully', $shop);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
