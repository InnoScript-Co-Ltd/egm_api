<?php

namespace App\Http\Controllers\Agent;

use App\Enums\GeneralStatusEnum;
use App\Enums\PackageBuyStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AgentPackageRequestStoreRequest;
use App\Models\AgentPackage;
use App\Models\Package;
use Exception;
use Illuminate\Support\Facades\DB;

class AgentPackageController extends Controller
{
    public function index()
    {
        $agent = auth('agent')->user();

        if ($agent && $agent->status === 'ACTIVE') {

            DB::beginTransaction();

            try {
                $agentPackage = AgentPackage::where(['agent_id' => $agent->id])
                    ->searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();

                DB::commit();

                return $this->success('agent package list is successfully retrived', $agentPackage);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }

    public function show($id)
    {
        $agent = auth('agent')->user();

        if ($agent) {
            DB::beginTransaction();

            try {
                $agentPackage = AgentPackage::findOrFail($id);

                DB::commit();

                return $this->success('agent transcation detail is successfully retrived', $agentPackage);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }

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

                $requestPackage = AgentPackage::where([
                    'agent_id' => $agent->id,
                    'package_id' => $package->id,
                ])->get()->first();

                if ($requestPackage && $requestPackage->status === PackageBuyStatusEnum::REQUEST->value) {
                    return $this->badRequest('Package is already request');
                }

                $agentPackageRequest = AgentPackage::create($payload->toArray());
                DB::commit();

                return $this->success('Agent package is requested successfully', $agentPackageRequest);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }
}
