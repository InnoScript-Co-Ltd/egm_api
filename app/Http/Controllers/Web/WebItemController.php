<?php

namespace App\Http\Controllers\Web;

use App\Models\Item;
use Illuminate\Support\Facades\DB;

class WebItemController extends WebController
{
    public function index()
    {
        $item = Item::with(['category'])
            ->searchQuery()
            ->sortingQuery()
            ->filterQuery()
            ->filterDateQuery()
            ->paginationQuery();
        DB::beginTransaction();
        try {

            DB::commit();

            return $this->success('Item list is successfully retrived', $item);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
