<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\MemberCardStoreRequest;
use App\Http\Requests\MemberCardUpdateRequest;
use App\Models\MemberCard;
use Exception;
use Illuminate\Support\Facades\DB;

class MemberCardController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $memberCards = MemberCard::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('member cards list is successfully retrived', $memberCards);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(MemberCardStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $memberCard = MemberCard::create($payload->toArray());
            DB::commit();

            return $this->success('Member card is created successfully', $memberCard);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $memberCard = MemberCard::with(['discounts'])->findOrFail($id);
            DB::commit();

            return $this->success('member card detail is successfully retrived', $memberCard);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(MemberCardUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();
        try {

            $memberCard = MemberCard::findOrFail($id);
            $memberCard->update($payload->toArray());
            DB::commit();

            return $this->success('Member card is updated successfully', $memberCard);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $memberCard = MemberCard::findOrFail($id);
            $memberCard->delete($id);
            DB::commit();

            return $this->success('Member card is deleted successfully', $memberCard);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
