<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\MPEItem;
use App\Http\Requests\MPEItemStoreRequest;
use App\Http\Requests\MPEItemUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MPEItemController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $item = MPEItem::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('MPE item list is successfully retrived', $item);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(MPEItemStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $item = MPEItem::create($payload->toArray());
            DB::commit();

            return $this->success('Mpe item is created successfully', $item);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $item = MPEItem::with('category', 'unit')->findOrFail($id);
            DB::commit();

            return $this->success('Mpe item details is successfully retrived', $item);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(MPEItemUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $item = MPEItem::findOrFail($id);
            $item->update($payload->toArray());
            DB::commit();

            return $this->success('Mpe item is updated successfully', $item);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destory($id)
    {
        DB::beginTransaction();
        try {

            $item= MPEItem::findOrFail($id);
            $item->delete($id);
            DB::commit();

            return $this->success('Mpe item is deleted successfully', $item);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
