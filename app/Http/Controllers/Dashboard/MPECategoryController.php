<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\MPECategory;
use Illuminate\Support\Facades\DB;

class MPECategoryController extends Controller
{
    public function index()
    {
        DB::connection('gsc_export')->beginTransaction();

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
}
