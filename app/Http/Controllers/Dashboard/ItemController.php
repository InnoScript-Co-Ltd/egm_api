<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ExportItem;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Imports\ImportItem;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
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

    public function store(ItemStoreRequest $request)
    {

        $payload = collect($request->validated());

        DB::beginTransaction();
        try {

            $item = Item::create($payload->toArray());

            if (isset($payload['thumbnail_photo']) && is_file($payload['thumbnail_photo'])) {
                $imagePath = $payload['thumbnail_photo']->store('images', 'public');
                $thumbnailPhoto = explode('/', $imagePath)[1];
                $item->thumbnailPhoto()->create([
                    'image' => $thumbnailPhoto,
                    'type' => 'thumbnail_photo',
                ]);
            }

            if (isset($payload['product_photo']) && is_array($payload['product_photo'])) {
                foreach ($payload['product_photo'] as $photo) {
                    $imagePath = $photo->store('images', 'public');
                    $productPhoto = explode('/', $imagePath)[1];
                    $item->productPhoto()->create([
                        'image' => $productPhoto,
                        'type' => 'product_photo',
                    ]);
                }
            }

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

            $item = Item::with(['thumbnailPhoto', 'productPhoto'])->findOrFail($id);
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

            if ($request->has('thumbnail_photo') && is_file($payload['thumbnail_photo'])) {
                $imagePath = $payload['thumbnail_photo']->store('images', 'public');
                $profileImage = explode('/', $imagePath)[1];
                $item->thumbnailPhoto()->updateOrCreate(['imageable_id' => $item->id], [
                    'image' => $profileImage,
                    'type' => 'thumbnail_photo',
                ]);
            }

            if ($request->has('product_photo') && is_array($payload['product_photo'])) {
                $item->productPhoto()->where('imageable_id', '=', $item->id)->delete();
                foreach ($payload['product_photo'] as $photo) {
                    $imagePath = $photo->store('images', 'public');
                    $profileImage = explode('/', $imagePath)[1];
                    $item->productPhoto()->create([
                        'image' => $profileImage,
                        'type' => 'product_photo',
                    ]);
                }
            }

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
