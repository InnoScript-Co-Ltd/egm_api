<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\MPECategoryStoreRequest;
use App\Http\Requests\MPECategoryUpdateRequest;
use App\Models\MPECategory;
use Illuminate\Support\Facades\DB;

class MPECategoryController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $category = MPECategory::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('MPE Category list is successfully retrived', $category);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(MPECategoryStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $category = MPECategory::create($payload->toArray());
            DB::commit();

            return $this->success('Mpe category is created successfully', $category);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $category = MPECategory::findOrFail($id);
            DB::commit();

            return $this->success('Mpe category details is successfully retrived', $category);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(MPECategoryUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $category = MPECategory::findOrFail($id);
            $category->update($payload->toArray());
            DB::commit();

            return $this->success('Mpe category is updated successfully', $category);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destory($id)
    {
        DB::beginTransaction();
        try {

            $category = MPECategory::findOrFail($id);
            $category->delete($id);
            DB::commit();

            return $this->success('Mpe category is deleted successfully', $category);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
