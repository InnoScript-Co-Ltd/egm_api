<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\TownshipStoreRequest;
use App\Http\Requests\TownshipUpdateRequest;
use App\Models\Township;
use Exception;
use Illuminate\Support\Facades\DB;

class TownshipController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $townships = Township::with(['city'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Townshop list is successfully retrived', $townships);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function townshipByCity($id)
    {
        DB::beginTransaction();
        try {

            $townships = Township::where([
                "status" => "ACTIVE",
                "city_id" => $id
            ])->get();
            DB::commit();

            return $this->success('Townshop list filter by city is successfully retrived', $townships);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(TownshipStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $township = Township::create($payload->toArray());
            DB::commit();

            return $this->success('Township is created successfully', $township);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $township = Township::with(['city'])->findOrFail($id);
            DB::commit();

            return $this->success('Township detail is successfully retrived', $township);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(TownshipUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {

            $township = Township::findOrFail($id);
            $township->update($payload->toArray());
            DB::commit();

            return $this->success('Township is updated successfully', $township);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $township = Township::findOrFail($id);
            $township->delete();
            DB::commit();

            return $this->success('Township is deleted successfully', $township);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
