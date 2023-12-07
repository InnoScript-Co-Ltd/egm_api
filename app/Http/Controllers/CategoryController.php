<?php

namespace App\Http\Controllers;

use App\Exports\ExportCategory;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $category = Category::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Category list is successfully retrived', $category);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(CategoryStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $category = Category::create($payload->toArray());
            DB::commit();

            return $this->success('Category is created successfully', $category);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {

        DB::beginTransaction();
        try {

            $category = Category::findOrFail($id);
            DB::commit();

            return $this->success('Category detail is successfully retrived', $category);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $category = Category::findOrFail($id);
            $category->update($payload->toArray());
            DB::commit();

            return $this->success('Category is updated successfully', $category);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $category = Category::findOrFail($id);
            $category->delete($id);
            DB::commit();

            return $this->success('Category is deleted successfully', $category);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new ExportCategory, 'categories.xlsx');
    }
}
