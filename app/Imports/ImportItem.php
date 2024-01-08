<?php

namespace App\Imports;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Snowflake;
use App\Models\Category;
use App\Models\Item;
use App\Models\Shop;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportItem implements ToModel, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $id = new Snowflake;
        $category = Category::where('title', $row['category_name'])->first();
        $categoryActive = $category->status === GeneralStatusEnum::ACTIVE->value;
        $shop = Shop::where('name', $row['shop_name'])->first();
        $shopActive = $shop->status === GeneralStatusEnum::ACTIVE->value;

        return new Item([
            'id' => $id,
            'category_id' => $categoryActive ? $category->id : null,
            'shop_id' => $shopActive ? $shop->id : null,
            'name' => $row['name'],
            'code' => $row['code'],
            'description' => $row['description'],
            'content' => $row['content'],
            'price' => $row['price'],
            'sell_price' => $row['sell_price'],
            'out_of_stock' => $row['out_of_stock'] ? $row['out_of_stock'] : 0,
            'instock' => $row['instock'] ? $row['instock'] : 0,
            'status' => $row['status'],
        ]);
    }
}
