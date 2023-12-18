<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

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
