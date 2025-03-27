<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Banner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerBannerController extends Controller
{
    public function index()
    {
        $partner = auth('partner')->user();
        if (($partner->kyc_status === 'FULL_KYC' || $partner->kyc_status === 'CHECKING') && $partner->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $banner = Banner::searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();

                DB::commit();

                return $this->success('Partner banner list is successfully retrived', $banner);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }
    public function show($id)
    {
        try {
            $banners = Banner::findOrFail($id);

            return $this->success('banner is retrived successfully', $banners);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
