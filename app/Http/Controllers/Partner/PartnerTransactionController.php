<?php

namespace App\Http\Controllers\Partner;

use App\Enums\PackageTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Partner\PartnerTransactionStoreRequest;
use App\Models\MerchantBankAccount;
use App\Models\Package;
use App\Models\PartnerBankAccount;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class PartnerTransactionController extends Controller
{
    public function store(PartnerTransactionStoreRequest $request)
    {
        $partner = auth('partner')->user();
        $id = $partner->id;

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            $payload = collect($request->validated());
            $payload['sender_id'] = $id;
            $payload['sender_type'] = PackageTypeEnum::PARTNER->value;
            $payload['transaction_type'] = TransactionTypeEnum::DEPOSIT->value;

            DB::beginTransaction();

            try {
                $payload['sender_name'] = $partner->first_name.' '.$partner->last_name;
                $payload['sender_email'] = $partner->email;
                $payload['sender_phone'] = $partner->phone;
                $payload['sender_nrc'] = $partner->nrc;
                $payload['sender_address'] = $partner->address;
                $payload['sender_account_name'] = $partner->agent_account_name;

                $bankAccount = PartnerBankAccount::findOrFail($payload['sender_account_id']);

                $payload['sender_account_name'] = $bankAccount->account_name;
                $payload['sender_account_number'] = $bankAccount->account_number;
                $payload['sender_bank_branch'] = $bankAccount->branch;
                $payload['sender_bank_address'] = $bankAccount->branch_address;
                $payload['bank_type'] = $bankAccount->bank_type;

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

                $payload['status'] = TransactionStatusEnum::DEPOSIT_PENDING->value;

                $transaction = Transaction::create($payload->toArray());
                DB::commit();

                return $this->success('partner deposit transaction is created successfully', $transaction);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }
}
