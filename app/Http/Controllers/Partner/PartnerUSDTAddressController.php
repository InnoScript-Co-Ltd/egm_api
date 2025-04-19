<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Partner\PartnerUSDTAddressStoreRequest;
use App\Http\Requests\Partner\PartnerUSDTAddressUpdateRequest;
use App\Models\USDTAddress;
use Exception;
use Illuminate\Support\Facades\DB;

class PartnerUSDTAddressController extends Controller
{
    public function index()
    {
        $partner = auth('partner')->user();

        try {
            $usdtAddress = USDTAddress::where(['partner_id' => $partner->id])
                ->searchQuery()
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
        $partner = auth('partner')->user();

        try {
            $usdtAddress = USDTAddress::with(['partner'])
                ->where(['partner_id' => $partner->id])
                ->findOrFail($id);

            return $this->success('USDTAddress is retrived successfully', $usdtAddress);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(PartnerUSDTAddressUpdateRequest $request, $id)
    {
        $partner = auth('partner')->user();

        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $usdtAddress = USDTAddress::where([
                'id' => $id,
                'partner_id' => $partner->id,
            ])
                ->update($payload->toArray());

            DB::commit();

            return $this->success('USDTAddress is updated successfully', $usdtAddress);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(PartnerUSDTAddressStoreRequest $request)
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
        $partner = auth('partner')->user();

        DB::beginTransaction();
        try {
            $usdtAddress = USDTAddress::where([
                'id' => $id,
                'partner_id' => $partner->id,
            ]);
            $usdtAddress->delete($id);
            DB::commit();

            return $this->success('USDTAddress is deleted successfully', $usdtAddress);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
