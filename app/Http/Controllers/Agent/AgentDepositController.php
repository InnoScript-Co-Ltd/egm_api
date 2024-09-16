<?php

namespace App\Http\Controllers\Agent;

use App\Enums\DepositStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AgentDepositStoreRequest;
use App\Models\AgentBankAccount;
use App\Models\Deposit;
use App\Models\MerchantBankAccount;
use App\Models\Package;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class AgentDepositController extends Controller
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

    public function store(AgentDepositStoreRequest $request)
    {
        $agent = auth('agent')->user();

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {
            $payload = collect($request->validated());

            DB::beginTransaction();

            try {
                $payload['sender_name'] = $agent->first_name.' '.$agent->last_name;
                $payload['sender_email'] = $agent->email;
                $payload['sender_phone'] = $agent->phone;
                $payload['sender_nrc'] = $agent->nrc;
                $payload['sender_address'] = $agent->address;
                $payload['sender_account_name'] = $agent->agent_account_name;

                $bankAccount = AgentBankAccount::findOrFail($payload['bank_account_id']);

                $payload['sender_account_name'] = $bankAccount->account_name;
                $payload['sender_account_number'] = $bankAccount->account_number;
                $payload['sender_bank_branch'] = $bankAccount->branch;
                $payload['sender_bank_address'] = $bankAccount->branch_address;
                $payload['sender_account_id'] = $bankAccount->id;

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
                $payload['sender_type'] = $agent->agent_type;
                $payload['sender_id'] = $agent->id;
                $payload['bank_type'] = $merchantBankAccount->bank_type;

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
