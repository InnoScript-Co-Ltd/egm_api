<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeliveryAddressStoreRequest;
use App\Http\Requests\DeliveryAddressUpdateRequest;
use App\Models\DeliveryAddress;
use Illuminate\Support\Facades\DB;

class DeliveryAddressController extends Controller
{
    public function index()
    {
        $deliveryAddress = DeliveryAddress::with(['users'])
            ->searchQuery()
            ->sortingQuery()
            ->paginationQuery();
        DB::beginTransaction();
        try {

            DB::commit();

            return $this->success('Delivery address list is successfully retrived', $deliveryAddress);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(DeliveryAddressStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $deliveryAddress = DeliveryAddress::create($payload->toArray());
            DB::commit();

            return $this->success('Delivery address is created successfully', $deliveryAddress);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $deliveryAddress = DeliveryAddress::findOrFail($id);
            DB::commit();

            return $this->success('Delivery address detail is successfully retrived', $deliveryAddress);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(DeliveryAddressUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $deliveryAddress = DeliveryAddress::findOrFail($id);
            $deliveryAddress->update($payload->toArray());
            DB::commit();

            return $this->success('Delivery address is updated successfully', $deliveryAddress);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {

            $deliveryAddress = DeliveryAddress::findOrFail($id);
            $deliveryAddress->delete($id);
            DB::commit();

            return $this->success('Delivery address is deleted successfully', $deliveryAddress);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
