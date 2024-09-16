<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Agent;
use Exception;
use Illuminate\Support\Facades\DB;

class PartnerAgentController extends Controller
{
    public function index()
    {
        $partner = auth('partner')->user();

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $agents = Agent::with(['deposit'])
                    ->where(['partner_id' => $partner->id])
                    ->searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();

                DB::commit();

                return $this->success('Agent list is successfully retrived', $agents);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }
}
