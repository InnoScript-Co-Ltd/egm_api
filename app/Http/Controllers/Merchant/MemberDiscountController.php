<?php

namespace App\Http\Controllers\Merchant;

use App\Models\MemberDiscount;
use Illuminate\Support\Facades\DB;

class MemberDiscountController extends Controller
{
    public function show($id)
    {
        DB::beginTransaction();

        try {
            $discount = MemberDiscount::find($id);
            DB::commit();

            return $this->success('member discount detail is successfully retrived', $discount);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
