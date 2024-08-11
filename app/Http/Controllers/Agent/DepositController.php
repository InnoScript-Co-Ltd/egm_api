<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\DepositStoreRequest;
use App\Models\BankAccount;
use App\Models\Deposit;
use App\Models\Package;
use Exception;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function store(DepositStoreRequest $request)
    {
        $agent = auth('agent')->user();
        $id = $agent->id;

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {
            $payload = collect($request->validated());
            $payload['agent_id'] = $id;

            DB::beginTransaction();

            try {
                $package = Package::findOrFail($payload['package_id']);
                $payload['name'] = $package->name;
                $payload['roi_rate'] = $package->roi_rate;
                $payload['duration'] = $package->duration;

                $payload['agent_name'] = $agent->first_name.' '.$agent->last_name;
                $payload['agent_email'] = $agent->email;
                $payload['agent_phone'] = $agent->phone;
                $payload['agent_nrc'] = $agent->nrc;
                $payload['agent_address'] = $agent->address;

                $bankAccount = BankAccount::findOrFail($payload['bank_account_id']);

                $payload['account_name'] = $bankAccount->account_name;
                $payload['account_number'] = $bankAccount->account_number;
                $payload['bank_type'] = $bankAccount->bank_type;
                $payload['branch'] = $bankAccount->branch;
                $payload['branch_address'] = $bankAccount->branch_address;

                if (isset($payload['transaction_screenshoot'])) {
                    $ImagePath = $payload['transaction_screenshoot']->store('images', 'public');
                    $image = explode('/', $ImagePath)[1];
                    $payload['transaction_screenshoot'] = $image;
                }

                $deposit = Deposit::create($payload->toArray());
                DB::commit();

                return $this->success('Deposit is created successfully', $deposit);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }
}
