<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Requests\MerchantDeviceStoreRequest;
use App\Models\Device;
use Exception;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    public function store(MerchantDeviceStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $merchant = Device::findOrFail($payload['user_id']);

            if ($merchant) {
                $device = Device::update($payload->toArray());
            } else {
                $device = Device::create($payload->toArray());
            }
            DB::commit();

            return $this->success('user device is updated or created successfully', $device);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
