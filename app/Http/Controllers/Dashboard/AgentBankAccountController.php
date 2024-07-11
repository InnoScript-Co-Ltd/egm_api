<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\AgentBankAccountStoreRequest;
use App\Http\Requests\AgentBankAccountUpdateRequest;
use App\Models\AgentBankAccount;
use Illuminate\Support\Facades\DB;
use Exception;

class AgentBankAccountController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $agentBankAccounts = AgentBankAccount::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('agent bank account list is successfully retrived', $agentBankAccounts);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(AgentBankAccountStoreRequest $request)
    {
        $payload = collect($request->validated());
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

    public function show($id)
    {
        DB::beginTransaction();

        try {
            $agentBankAccount = AgentBankAccount::findOrFail($id);
            DB::commit();

            return $this->success('Agent back account is updated successfully retrived', $agentBankAccount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(AgentBankAccountUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $agentBankAccount = AgentBankAccount::findOrFail($id);
            $agentBankAccount->update($payload->toArray());

            DB::commit();

            return $this->success('Agent bank account is updated successfully', $agentBankAccount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $agentBankAccount = AgentBankAccount::findOrFail($id);
            $agentBankAccount->delete();
            DB::commit();

            return $this->success('Agent bank account is deleted successfully', $agentBankAccount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
