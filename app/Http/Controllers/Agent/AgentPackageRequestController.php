<?php

namespace App\Http\Controllers\Agent;

use App\Enums\GeneralStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AgentPackageRequestStoreRequest;
use App\Models\AgentPackageRequest;
use App\Models\Package;
use Exception;
use Illuminate\Support\Facades\DB;

class AgentPackageRequestController extends Controller
{
    public function store(AgentPackageRequestStoreRequest $request)
    {
        $payload = collect($request->validated());
        $agent = auth('agent')->user();

        if ($agent) {
            DB::beginTransaction();

            try {
                $payload['agent_id'] = $agent->id;
                $payload['agent_name'] = $agent->first_name.' '.$agent->last_name;
                $payload['agent_email'] = $agent->email;
                $payload['agent_phone'] = $agent->phone;

                $package = Package::where([
                    'status' => GeneralStatusEnum::ACTIVE->value,
                    'id' => $payload['package_id'],
                ])->get()->first();

                if ($package) {
                    $payload['package_name'] = $package->name;
                    $payload['package_roi_rate'] = $package->roi_rate;
                    $payload['package_duration'] = $package->duration;
                    $payload['package_deposit_rate'] = $package->deposit_rate;
                }

                $agentPackageRequest = AgentPackageRequest::create($payload->toArray());
                DB::commit();

                return $this->success('Agent package request is created successfully', $agentPackageRequest);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }
}
