<?php

namespace App\Http\Controllers;

use App\Http\Requests\PointStoreRequest;
use App\Http\Requests\PointUpdateRequest;
use App\Models\Point;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $points = Point::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('Point list is successfully retrived', $points);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {
            $point = Point::findOrFail($id);
            DB::commit();

            return $this->success('Point detail is successfully retrived', $point);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(PointUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {

            $point = Point::findOrFail($id);
            $point->update($payload->toArray());
            DB::commit();

            return $this->success('Point is updated successfully', $point);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(PointStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $point = Point::create($payload->toArray());
            DB::commit();

            return $this->success('Point is created successfully', $point);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $point = Point::findOrFail($id);
            $point->delete($id);

            DB::commit();

            return $this->success('Point is deleted successfully', $point);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
