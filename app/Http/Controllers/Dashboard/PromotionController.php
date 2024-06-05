<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\PromotionItemStoreRequest;
use App\Http\Requests\PromotionItemUpdateRequest;
use App\Http\Requests\PromotionStoreRequest;
use App\Http\Requests\PromotionUpdateRequest;
use App\Models\Promotion;
use App\Models\PromotionInItem;
use Exception;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $promotion = Promotion::with(['image'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('Promotion list is successfully retrived', $promotion);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(PromotionStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $promotion = Promotion::create($payload->toArray());
            $imagePath = $payload['image']->store('images', 'public');
            $imageName = explode('/', $imagePath)[1];
            $promotion->image()->create([
                'image' => $imageName,
                'type' => 'image',
            ]);
            $promotion['image'] = $imageName;

            DB::commit();

            return $this->success('Promotion is created successfully', $promotion);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function show($id)
    {
        DB::beginTransaction();

        try {
            $promotion = Promotion::with([
                'image',
                'items' => fn ($query) => $query->where('status', '!=', 'DELETED')->with(['item']),
            ])
                ->findOrFail($id);
            DB::commit();

            return $this->success('Promotion detail is successfully retrived', $promotion);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(PromotionUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $promotion = Promotion::with(['image'])->findOrFail($id);
            $promotion->update($payload->toArray());

            if (isset($payload['image'])) {
                $imagePath = $payload['image']->store('images', 'public');
                $imageName = explode('/', $imagePath)[1];
                $promotion->image()->updateOrCreate(['imageable_id' => $promotion->id], [
                    'image' => $imageName,
                    'type' => 'image',
                ]);
                $promotion['image'] = $imageName;
            }
            DB::commit();

            return $this->success('Promotion is updated successfully', $promotion);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function destory($id)
    {
        DB::beginTransaction();

        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->delete();
            DB::commit();

            return $this->success('Promotion is deleted successfully', $promotion);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function storeItem(PromotionItemStoreRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $promotion = Promotion::findOrFail($id);
            collect($payload['item_ids'])->map(function ($item) use ($id, $payload) {
                PromotionInItem::create([
                    'promotion_id' => $id,
                    'item_id' => $item,
                    'promotion_price' => $payload['promotion_price'],
                    'status' => 'ACTIVE',
                ]);
            });

            $promotion['items'] = $payload['item_ids'];
            DB::commit();

            return $this->success('Promotion item are created successfully', $promotion);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function showItem($id)
    {
        DB::beginTransaction();

        try {

            $promotionItems = PromotionInItem::where('promotion_id', '=', $id)
            // ->with(['item'])
            ->get()
            ->toArray();

            // Extract 'name' and 'id' from each item
            // $formattedItems = array_map(function($promotionItem) {
            //     return [
            //         'id' => $promotionItem['id'],
            //         'item_id' => $promotionItem['item_id'],
            //         'promotion_id' => $promotionItem['promotion_id'],
            //         'promotion_price' => $promotionItem['promotion_price'],
            //         'status' => $promotionItem['status'],
            //         'item' => [
            //             'id' => $promotionItem['item']['id'],
            //             'name' => $promotionItem['item']['name']
            //         ]
            //     ];
            // }, $promotionItems);

            DB::commit();

            return $this->success('Promotion in item list is retrived successfully', $promotionItems);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroyItem($id)
    {
        DB::beginTransaction();
        try {

            $promotionItem = PromotionInItem::findOrFail($id);
            $promotionItem->delete();
            DB::commit();

            return $this->success('Promotion in item is deleted successfully', $promotionItem);

        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function updateItem(PromotionItemUpdateRequest $request)
    {
        $payload = collect($request->validated());
        $itemId = $request['itemId'];

        DB::beginTransaction();

        try {
            $promotionItem = PromotionInItem::with(['item'])->findOrFail($itemId);
            $promotionItem->update($payload->toArray());
            DB::commit();

            return $this->success('Promotion item is updated successfully', $promotionItem);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
