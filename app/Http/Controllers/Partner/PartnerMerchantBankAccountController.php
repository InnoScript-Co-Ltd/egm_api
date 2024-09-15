<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\MerchantBankAccount;
use Exception;
use Illuminate\Support\Facades\DB;

class PartnerMerchantBankAccountController extends Controller
{
    public function index()
    {
        $partner = auth('partner')->user();

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $merchantBankAccounts = MerchantBankAccount::searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();

                DB::commit();

                return $this->success('Partner merchant bank account list is successfully retrived', $merchantBankAccounts);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }
}
