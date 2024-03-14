<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\MPEUnit;
use App\Http\Requests\MPEUnitStoreRequest;
use App\Http\Requests\MPEUnitUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MPEUnitController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $unit = MPEUnit::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('MPE unit list is successfully retrived', $unit);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(MPEUnitStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $unit = MPEUnit::create($payload->toArray());
            DB::commit();

            return $this->success('Mpe unit is created successfully', $unit);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $unit = MPEUnit::findOrFail($id);
            DB::commit();

            return $this->success('Mpe unit details is successfully retrived', $unit);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(MPEUnitUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $unit = MPEUnit::findOrFail($id);
            $unit->update($payload->toArray());
            DB::commit();

            return $this->success('Mpe unit is updated successfully', $unit);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destory($id)
    {
        DB::beginTransaction();
        try {

            $unit = MPEUnit::findOrFail($id);
            $unit->delete($id);
            DB::commit();

            return $this->success('Mpe unit is deleted successfully', $unit);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
