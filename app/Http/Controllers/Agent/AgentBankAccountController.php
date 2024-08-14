<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AgentBankAccountStoreRequest;
use App\Http\Requests\Agents\AgentBankAccountUpdateRequest;
use App\Models\AgentBankAccount;
use Exception;
use Illuminate\Support\Facades\DB;

class AgentBankAccountController extends Controller
{
    public function index()
    {
        $agent = auth('agent')->user();
        $id = $agent->id;

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $agentBankAccounts = AgentBankAccount::where(['agent_id' => $id])->get();
                DB::commit();

                return $this->success('Agent bank account list is successfully retrived', $agentBankAccounts);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }

    public function store(AgentBankAccountStoreRequest $request)
    {
        $agent = auth('agent')->user();
        $id = $agent->id;

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {
            $payload = collect($request->validated());
            $payload['agent_id'] = $id;

            DB::beginTransaction();

            try {
                $agentBankAccount = AgentBankAccount::create($payload->toArray());
                DB::commit();

                return $this->success('New agent bank account is created successfully', $agentBankAccount);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }

    public function update(AgentBankAccountUpdateRequest $request, $id)
    {
        $agent = auth('agent')->user();
        $agentId = $agent->id;

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {
            $payload = collect($request->validated());
            DB::beginTransaction();

            try {
                $agentBankAccount = AgentBankAccount::where([
                    'id' => $id,
                    'agent_id' => $agentId,
                ])->get()->first();

                $agentBankAccount->update($payload->toArray());
                DB::commit();

                return $this->success('New agent bank account is updated successfully', $agentBankAccount);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }

    public function destroy($id)
    {
        $agent = auth('agent')->user();
        $agentId = $agent->id;

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $agentBankAccount = AgentBankAccount::where([
                    'id' => $id,
                    'agent_id' => $agentId,
                ])->get()->first();

                $agentBankAccount->delete($id);
                DB::commit();

                return $this->success('Agent bank account is deleted successfully', $agentBankAccount);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }
}
