<?php

namespace App\Http\Controllers\Client;

use App\Enums\GeneralStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Models\Promotion;
use Exception;
use Illuminate\Support\Facades\DB;

class ClientPromotionController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {
            $promotions = Promotion::with(['image'])
                ->where(['status' => GeneralStatusEnum::ACTIVE->value]
                )->get();
            DB::commit();

            return $this->success('Promotion list is successfully retrived', $promotions);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
