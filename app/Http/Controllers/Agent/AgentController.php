<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Agent;
use Exception;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    public function show($id)
    {

        $agent = auth('agent')->user();

        if ($agent && $agent->status === 'ACTIVE' && $agent->kyc_status === 'FULL_KYC') {

            DB::beginTransaction();

            try {
                $agent = Agent::with(['deposit'])
                    ->findOrFail($id);

                DB::commit();

                return $this->success('Agent is retrived successfully', $agent);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }
}
