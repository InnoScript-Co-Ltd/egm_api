<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Region;
use App\Http\Requests\RegionStoreRequest;
use App\Http\Requests\RegionUpdateRequest;

class RegionController extends Controller
{
    public function index ()
    {
        DB::beginTransaction();
        try {
            
            $region = Region::searchQuery()
                      ->sortingQuery()
                      ->paginationQuery();
            DB::commit();
            
            return $this->success('Region list is successfully retrived', $region);

        } catch (Exception $e) {
        DB::rollback();
        throw $e;
        }
    }

    public function store(RegionStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $region = Region::create($payload->toArray());
            DB::commit();

            return $this->success('Region is created successfully', $region);

        } catch (Exception $e) {
        DB::rollback();
        throw $e;
        }
    }

    public function show ($id)
    {
        DB::beginTransaction();
        try {
            
            $region = Region::findOrFail($id);
            DB::commit();

            return $this->success('Region detail is successfully retrived',$region);

        } catch (Exception $e) {
        DB::rollback();
        throw $e;
        }
    }

    public function update(RegionUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {
            
            $region = Region::findOrFail($id);
            $region->update($payload->toArray());
            DB::commit();

            return $this->success('Region is updated successfully', $region);

        } catch (Exception $e) {
        DB::rollback();
        throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            
            $region = Region::findOrFail($id);
            $region->delete($id);
            DB::commit();

            return $this->success('Region is deleted successfully', $region);

        } catch (Exception $e) {
        DB::rollback();
        throw $e;
        }
    }
}
