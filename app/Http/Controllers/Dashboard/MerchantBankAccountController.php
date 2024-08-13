<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\MerchantBankAccountStoreRequest;
use App\Http\Requests\Dashboard\MerchantBankAccountUpdateRequest;
use App\Models\MerchantBankAccount;
use Exception;
use Illuminate\Support\Facades\DB;

class MerchantBankAccountController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $merchantBankAccount = MerchantBankAccount::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('merchant bank account list is successfully retrived', $merchantBankAccount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
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
        DB::beginTransaction();
        try {

            $merchantBankAccount = MerchantBankAccount::findOrFail($id);
            DB::commit();

            return $this->success('merchant bank account detail is successfully retrived', $merchantBankAccount);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
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
            throw $e;
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
            throw $e;
        }
    }
}
