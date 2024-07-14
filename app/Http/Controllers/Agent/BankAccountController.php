<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\BankAccountStoreRequest;
use App\Models\AgentBankAccount;
use Exception;
use Illuminate\Support\Facades\DB;

class BankAccountController extends Controller
{
    public function index()
    {
        $id = auth('agent')->user()->id;

        DB::beginTransaction();

        try {
            $bankAccounts = AgentBankAccount::where(['agent_id' => $id])->get();
            DB::commit();

            return $this->success('agent bank account list is successfully retrived', $bankAccounts);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(BankAccountStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $id = auth('agent')->user()->id;
            $payload['agent_id'] = $id;
            $agentBankAccount = AgentBankAccount::create($payload->toArray());
            DB::commit();

            return $this->success('New agent bank account is created successfully', $agentBankAccount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
