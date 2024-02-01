<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\MemberDiscountStoreRequest;
use App\Models\MemberDiscount;
use Illuminate\Support\Facades\DB;

class MemberDiscountController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $memberDiscounts = MemberDiscount::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('member discount list is successfully retrived', $memberDiscounts);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(MemberDiscountStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $memberDiscount = MemberDiscount::create($payload->toArray());
            DB::commit();

            return $this->success('Member discount is created successfully', $memberDiscount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $memberDiscount = MemberDiscount::findOrFail($id);
            DB::commit();

            return $this->success('member discount detail is successfully retrived', $memberDiscount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(MemberDiscountUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $memberDiscount = MemberDiscount::findOrFail($id);
            $memberDiscount->update($payload->toArray());
            DB::commit();

            return $this->success('Member discount is updated successfully', $memberDiscount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $memberDiscount = MemberDiscount::findOrFail($id);
            $memberDiscount->delete($id);
            DB::commit();

            return $this->success('Member Disocunt is deleted successfully', $memberDiscount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
