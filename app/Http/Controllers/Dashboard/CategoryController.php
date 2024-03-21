<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ExportCategory;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Imports\ImportCategory;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $category = Category::with(['icon'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
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

            if (isset($payload['icon'])) {
                $imagePath = $payload['icon']->store('images', 'public');
                $iconImage = explode('/', $imagePath)[1];
                $category->icon()->updateOrCreate(['imageable_id' => $category->id], [
                    'image' => $iconImage,
                    'imageable_id' => $category->id,
                ]);
                $category['icon'] = $iconImage;
            }

            DB::commit();

            return $this->success('New category is created successfully', $category);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {

        DB::beginTransaction();
        try {

            $category = Category::with(['icon'])->findOrFail($id);
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

            if (isset($payload['icon'])) {
                $imagePath = $payload['icon']->store('images', 'public');
                $iconImage = explode('/', $imagePath)[1];
                $category->icon()->updateOrCreate(['imageable_id' => $category->id], [
                    'image' => $iconImage,
                    'imageable_id' => $category->id,
                ]);
                $category['icon'] = $iconImage;
            }

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

    public function export()
    {
        return Excel::download(new ExportCategory, 'categories.xlsx');
    }

    public function import()
    {
        Excel::import(new ImportCategory, request()->file('file'));

        return $this->success('Category is imported successfully');
    }
}
