<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Promotion;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    protected $active = [
        'status' => 'ACTIVE',
        'app_type' => 'GSCEXPORT',
    ];

    protected $showalbeFields = [
        'id',
        'title',
        'app_type',
        'start_date',
        'end_date',
    ];

    public function index()
    {
        DB::beginTransaction();

        try {
            $promotions = Promotion::with([
                'image',
                'items' => fn ($query) => $query->where(['status' => 'ACTIVE'])->with(['item']),
            ])
                ->where($this->active)
                ->whereDate('end_date', '>', Carbon::now())
                ->select($this->showalbeFields)
                ->get();

            DB::commit();

            return $this->success('promotions list is successfully retrived', $promotions);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {
            $promotion = Promotion::with(['image', 'items'])
                ->where($this->active)
                ->where(['id' => $id])
                ->select($this->showalbeFields)
                ->first();

            DB::commit();

            return $this->success('promotions detail is successfully retrived', $promotion);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
