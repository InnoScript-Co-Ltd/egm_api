<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\AdminBankTypeStoreRequest;
use App\Http\Requests\Dashboard\AdminBankTypeUpdateRequest;
use App\Models\BankType;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminBankTypeController extends Controller
{
    public function index()
    {
        try {
            $bankTypes = BankType::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('bank type list is successfully retrived', $bankTypes);

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function store(AdminBankTypeStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        if (isset($payload['logo'])) {
            $ImagePath = $payload['logo']->store('images', 'public');
            $image = explode('/', $ImagePath)[1];
            $payload['logo'] = $image;
        }

        try {
            $bankType = BankType::create($payload->toArray());
            DB::commit();

            return $this->success('New bank type is created successfully', $bankType);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $bankType = BankType::findOrFail($id);

            return $this->success('Bank type is updated successfully retrived', $bankType);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(AdminBankTypeUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        if (isset($payload['logo'])) {
            $ImagePath = $payload['logo']->store('images', 'public');
            $image = explode('/', $ImagePath)[1];
            $payload['logo'] = $image;
        }

        DB::beginTransaction();
        try {
            $bankType = BankType::findOrFail($id);
            $bankType->update($payload->toArray());

            DB::commit();

            return $this->success('Bank type is updated successfully', $bankType);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $bankType = BankType::findOrFail($id);
            $bankType->delete();
            DB::commit();

            return $this->success('Bank type is deleted successfully', $bankType);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
