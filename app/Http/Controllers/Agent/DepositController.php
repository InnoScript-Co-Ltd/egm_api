<?php

namespace App\Http\Controllers\Agent;

use App\Enums\DepositStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\DepositStoreRequest;
use App\Models\AgentBankAccount;
use App\Models\Deposit;
use App\Models\MerchantBankAccount;
use App\Models\Package;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function index()
    {
        $agent = auth('agent')->user();

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {

            DB::beginTransaction();

            try {
                $deposits = Deposit::where(['agent_id' => $agent->id])
                    ->searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();
                DB::commit();

                return $this->success('Agent deposit transactions are retrived successfully', $deposits);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

    }

    public function store(DepositStoreRequest $request)
    {
        $agent = auth('agent')->user();
        $id = $agent->id;

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {
            $payload = collect($request->validated());
            $payload['agent_id'] = $id;

            DB::beginTransaction();

            try {
                $payload['agent_name'] = $agent->first_name.' '.$agent->last_name;
                $payload['agent_email'] = $agent->email;
                $payload['agent_phone'] = $agent->phone;
                $payload['agent_nrc'] = $agent->nrc;
                $payload['agent_address'] = $agent->address;
                $payload['agent_account_name'] = $agent->agent_account_name;

                $bankAccount = AgentBankAccount::findOrFail($payload['bank_account_id']);

                $payload['agent_account_name'] = $bankAccount->account_name;
                $payload['agent_account_number'] = $bankAccount->account_number;
                $payload['agent_bank_branch'] = $bankAccount->branch;
                $payload['agent_bank_address'] = $bankAccount->branch_address;

                $merchantBankAccount = MerchantBankAccount::findOrFail($payload['merchant_account_id']);

                $payload['merchant_account_name'] = $merchantBankAccount->holder_name;
                $payload['merchant_account_number'] = $merchantBankAccount->account_number;

                $package = Package::findOrFail($payload['package_id']);

                $payload['package_name'] = $package->name;
                $payload['package_roi_rate'] = $package->roi_rate;
                $payload['package_duration'] = $package->duration;

                if (isset($payload['transaction_screenshoot'])) {
                    $ImagePath = $payload['transaction_screenshoot']->store('images', 'public');
                    $image = explode('/', $ImagePath)[1];
                    $payload['transaction_screenshoot'] = $image;
                }

                $payload['transaction_type'] = TransactionTypeEnum::DEPOSIT->value;
                $payload['status'] = DepositStatusEnum::DEPOSIT_PENDING->value;

                $deposit = Transaction::create($payload->toArray());
                DB::commit();

                return $this->success('Deposit is created successfully', $deposit);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }
}
