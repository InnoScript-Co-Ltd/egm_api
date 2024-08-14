<?php

namespace App\Http\Controllers\Agent;

use App\Enums\GeneralStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Models\MerchantBankAccount;
use Exception;
use Illuminate\Support\Facades\DB;

class MerchantBankAccountController extends Controller
{
    public function index()
    {
        $agent = auth('agent')->user();

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $merchantBankAccount = MerchantBankAccount::where(['status' => GeneralStatusEnum::ACTIVE->value])->get();
                DB::commit();

                return $this->success('Merchant bank account list is successfully retrived', $merchantBankAccount);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }
}
