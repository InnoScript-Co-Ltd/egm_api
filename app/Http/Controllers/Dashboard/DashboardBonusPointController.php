<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\DashboardBonusPointStoreRequest;
use App\Http\Requests\Dashboard\DashboardBonusPointUpdateRequest;
use App\Models\BonusPoint;
use Exception;
use Illuminate\Support\Facades\DB;

class DashboardBonusPointController extends Controller
{
    public function index()
    {
        try {
            $bonusPoints = BonusPoint::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Bonus points are retrived successfully', $bonusPoints);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $bonusPoint = BonusPoint::findOrFail($id);

            return $this->success('Bonus point is retrived successfully', $bonusPoint);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(DashboardBonusPointUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $bonusPoint = BonusPoint::findOrFail($id);
            $bonusPoint->update($payload->toArray());
            DB::commit();

            return $this->success('Bonus point is updated successfully', $bonusPoint);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(DashboardBonusPointStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $bonusPoint = BonusPoint::create($payload->toArray());
            DB::commit();

            return $this->success('Bonus point is created successfully', $bonusPoint);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $bounsPoint = BonusPoint::findOrFail($id);
            $bonusPoint->delete();
            DB::commit();

            return $this->success('Bonus point is deleted successfully', $bonusPoint);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
