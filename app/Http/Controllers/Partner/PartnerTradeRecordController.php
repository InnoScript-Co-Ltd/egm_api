<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\TradeRecord;
use Exception;
use Illuminate\Support\Facades\DB;

class PartnerTradeRecordController extends Controller
{
    //

  
    public function index()
    {
        $partner = auth('partner')->user();
        $id = $partner->id;
        if (in_array($partner->kyc_status, ['FULL_KYC', 'CHECKING']) && $partner->status === 'ACTIVE') {
            
            DB::beginTransaction();

            try {
                $traderecord = TradeRecord::searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();

                DB::commit();
                return $this->success('Trade Records list is successfully retrived', $traderecord);
            } catch (Exception $e) {
                DB::rollback();
              throw $e;
            }
        }

        return $this->badRequest('You account is not active');
    }
    public function show($id)
    {
        $partner = auth('partner')->user();
        if (in_array($partner->kyc_status, ['FULL_KYC', 'CHECKING']) && $partner->status === 'ACTIVE') {
            DB::beginTransaction();
    
            try {
                $tradeRecord = TradeRecord::findOrFail($id);
                DB::commit();
    
                return $this->success('tradeRecord is retrived successfully', $tradeRecord);
            } catch (Exception $e) {
                DB::rollback();
                dd($e);
                throw $e;
            }
        }
    }


}