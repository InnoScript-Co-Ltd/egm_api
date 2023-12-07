<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotionStoreRequest;
use App\Http\Requests\PromotionUpdateRequest;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    public function index()
    {
        $promotion = Promotion::searchQuery()
            ->sortingQuery()
            ->paginationQuery();
        DB::beginTransaction();
        try {

            DB::commit();

            return $this->success('Promotion list is successfully retrived', $promotion);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(PromotionStoreRequest $request)
    {

        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $promotion = Promotion::create($payload->toArray());
            DB::commit();

            return $this->success('Promotion is created successfully', $promotion);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $promotion = Promotion::findOrFail($id);
            DB::commit();

            return $this->success('Promotion detail is successfully retrived', $promotion);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(PromotionUpdateRequest $request, $id)
    {

        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $promotion = Promotion::findOrFail($id);
            $promotion->update($payload->toArray());
            DB::commit();

            return $this->success('Promotion is updated successfully', $promotion);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function destory($id)
    {
        DB::beginTransaction();
        try {

            $promotion = Promotion::findOrFail($id);
            $promotion->delete($id);
            DB::commit();

            return $this->success('Promotion is deleted successfully', $promotion);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
