<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\DashboardRepaymentUpdateRequest;
use App\Models\Repayment;
use Exception;
use Illuminate\Support\Facades\DB;

class DashboardRepaymentController extends Controller
{
    public function index()
    {
        try {
            $repayments = Repayment::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Repayments are retrived successfully', $repayments);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $repayment = Repayment::findOrFail($id);

            return $this->success('Repayment is retrived successfully', $repayment);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(DashboardRepaymentUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $repayment = Repayment::findOrFail($id);
            $repayment->update($payload->toArray());
            DB::commit();

            return $this->success('Repayment is updated successfully', $repayment);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
