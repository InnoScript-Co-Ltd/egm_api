<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Package;
use Exception;
use Illuminate\Support\Facades\DB;

class PartnerDepositPackageController extends Controller
{
    public function index()
    {
        $partner = auth('partner')->user();
        $id = $partner->id;

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $depositPackages = Package::searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();

                DB::commit();

                return $this->success('Deposit package list is successfully retrived', $depositPackages);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You account is not active');
    }
}
