<?php

namespace App\Http\Controllers;

use App\Exports\ExportItem;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function index()
    {
        $item = Item::with(['category'])
            ->searchQuery()
            ->sortingQuery()
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

    public function store(ItemStoreRequest $request)
    {

        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $item = Item::create($payload->toArray());
            DB::commit();

            return $this->success('Item is created successfully', $item);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $item = Item::findOrFail($id);
            DB::commit();

            return $this->success('Item detail is successfully retrived', $item);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(ItemUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $item = Item::findOrFail($id);
            $item->update($payload->toArray());
            DB::commit();

            return $this->success('Item is updated successfully', $item);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {

            $item = Item::findOrFail($id);
            $item->delete($id);
            DB::commit();

            return $this->success('Item is deleted successfully', $item);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function export()
    {
        return Excel::download(new ExportItem, 'Items.xlsx');
    }
}
