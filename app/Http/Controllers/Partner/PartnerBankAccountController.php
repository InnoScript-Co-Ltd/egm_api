<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Partner\PartnerBankAccountStoreRequest;
use App\Http\Requests\Partner\PartnerBankAccountUpdateRequest;
use App\Models\PartnerBankAccount;
use Exception;
use Illuminate\Support\Facades\DB;

class PartnerBankAccountController extends Controller
{
    public function index()
    {
        $partner = auth('partner')->user();
        $id = $partner->id;

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $partnerBankAccounts = PartnerBankAccount::where(['partner_id' => $id])
                    ->searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();

                DB::commit();

                return $this->success('Partner bank account list is successfully retrived', $partnerBankAccounts);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }

    public function store(PartnerBankAccountStoreRequest $request)
    {
        $partner = auth('partner')->user();
        $id = $partner->id;

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            $payload = collect($request->validated());
            $payload['partner_id'] = $id;

            DB::beginTransaction();

            try {
                $partnerBankAccount = PartnerBankAccount::create($payload->toArray());
                DB::commit();

                return $this->success('New partner bank account is created successfully', $partnerBankAccount);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }

    public function update(PartnerBankAccountUpdateRequest $request, $id)
    {
        $partner = auth('partner')->user();

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            $payload = collect($request->validated());

            DB::beginTransaction();

            try {
                $partnerBankAccount = PartnerBankAccount::findOrFail($id);
                $partnerBankAccount->update($payload->toArray());
                DB::commit();

                return $this->success('Partner bank account is updated successfully', $partnerBankAccount);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }

    public function show($id)
    {
        $partner = auth('partner')->user();

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            try {
                $partnerBankAccount = PartnerBankAccount::with(['transactions'])
                    ->findOrFail($id);

                return $this->success('partner bank account is retrieved successfully', $partnerBankAccount);
            } catch (Exception $e) {
                return $this->internalServerError('Something went wrong');
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
