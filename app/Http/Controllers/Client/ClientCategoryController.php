<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;

class ClientCategoryController extends Controller
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
            throw $e;
            DB::rollback();
        }
    }
}
