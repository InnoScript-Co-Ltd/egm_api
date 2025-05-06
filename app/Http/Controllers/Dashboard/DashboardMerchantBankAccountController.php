<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\MerchantBankAccountStoreRequest;
use App\Http\Requests\Dashboard\MerchantBankAccountUpdateRequest;
use App\Models\MerchantBankAccount;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class DashboardMerchantBankAccountController extends Controller
{
    public function index()
    {
        try {

            $merchantBankAccount = MerchantBankAccount::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Merchant bank account list is successfully retrived', $merchantBankAccount);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError('Something went wrong while retrieving merchant bank account list');
        }
    }

    public function store(MerchantBankAccountStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $merechantBankAccount = MerchantBankAccount::create($payload->toArray());
            DB::commit();

            return $this->success('Merchant bank account is created successfully', $merechantBankAccount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $merchantBankAccount = MerchantBankAccount::findOrFail($id);
            $merchantBankAccount['transactions'] = Transaction::where('merchant_account_id', $id)->get();
            $merchantBankAccount['total_deposit_amount'] = $merchantBankAccount['transactions']->sum('package_deposit_amount');
            $merchantBankAccount['this_month_deposit_amount'] = $merchantBankAccount['transactions']->where('created_at', '>=', now()->startOfMonth())->sum('package_deposit_amount');

            return $this->success('merchant bank account detail is successfully retrived', $merchantBankAccount);
        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError('Something went wrong while retrieving merchant bank account detail');
        }
    }

    public function update(MerchantBankAccountUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $merchantBankAccount = MerchantBankAccount::findOrFail($id);
            $merchantBankAccount->update($payload->toArray());
            DB::commit();

            return $this->success('merchant bank account is updated successfully', $merchantBankAccount);
        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError('Something went wrong while updating merchant bank account');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $merchantBankAccount = MerchantBankAccount::findOrFail($id);
            $merchantBankAccount->delete();
            DB::commit();

            return $this->success('Merchant bank account is deleted successfully', $merchantBankAccount);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError('Something went wrong while deleting merchant bank account');
        }
    }
}
