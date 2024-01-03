<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ExportItem;
use App\Imports\ImportItem;
use App\Helpers\Snowflake;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\File;
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

    public function store(ItemStoreRequest $request)
    {

        $payload = collect($request->validated());

        $files = collect($payload['images'])->map(function ($image) {
            $image_path = $image->store('images', 'public');
            $name = explode('/', $image_path)[1];
            $snowflake = new SnowFlake;

            return [
                'id' => $snowflake->id(),
                'name' => $name,
                'category' => 'ITEM',
                'size' => $image->getSize(),
                'type' => $image->getMimeType(),
            ];
        });

        DB::beginTransaction();
        try {

            $uploadFile = File::insert($files->toArray());

            if ($uploadFile === true) {
                $payload['images'] = $files->map(function ($file) {
                    return $file['id'];
                })->toArray();

                $item = Item::create($payload->toArray());
                DB::commit();

                return $this->success('Item is created successfully', $item);

            } else {
                DB::commit();

                return $this->validationError('Item is created fialed', [
                    'images' => ['can not upload image files'],
                ]);
            }

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

    public function destroy($id)
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

    public function import() 
    {
        Excel::import(new ImportItem, request()->file('file'));
        return $this->success('Item is imported successfully');
    }
}
