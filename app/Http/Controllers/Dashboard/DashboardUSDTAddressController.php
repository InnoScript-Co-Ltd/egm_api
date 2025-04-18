<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\USDTAddress;
use Exception;
use Illuminate\Support\Facades\DB;

class DashboardUSDTAddressController extends Controller
{
    public function index()
    {
        try {
            $usdtAddress = USDTAddress::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('USDTAddress are retrived successfully', $usdtAddress);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $usdtAddress = USDTAddress::with(['partner'])->findOrFail($id);

            return $this->success('USDTAddress is retrived successfully', $usdtAddress);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(USDTAddressUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $usdtAddress = USDTAddress::findOrFail($id);
            $usdtAddress->update($payload->toArray());
            DB::commit();

            return $this->success('USDTAddress is updated successfully', $usdtAddress);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(USDTAddressStoreRequest $request)
    {

        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $usdtAddress = USDTAddress::create($payload->toArray());
            DB::commit();

            return $this->success('USDTAddress is created successfully', $usdtAddress);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $usdtAddress = USDTAddress::findOrFail($id);
            $usdtAddress->delete();
            DB::commit();

            return $this->success('USDTAddress is deleted successfully', $usdtAddress);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
