<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Item;
use Exception;
use Illuminate\Support\Facades\DB;

class ClientItemController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {

            $item = Item::with(['thumbnailPhoto', 'productPhoto'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('Item list is successfully retrived', $item);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $item = Item::with(['thumbnailPhoto', 'productPhoto'])->findOrFail($id);
            DB::commit();

            return $this->success('Item detail is successfully retrived', $item);

        } catch (Exception $e) {
            throw $e;
            DB::rollback();
        }
    }
}
