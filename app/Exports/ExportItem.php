<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportItem implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Item::select('id', 'category_id', 'shop_id', 'name', 'item_color', 'item_size', 'item_code', 'description', 'content', 'price', 'sell_price', 'instock', 'status')->get();
    }

    public function headings(): array
    {
        return [
            'Id',
            'Category Name',
            'Shop Name',
            'Name',
            'Item Color',
            "Item Size",
            "Item Code",
            'Description',
            'Content',
            'Price',
            'Sell Price',
            'Instock',
            'Status',
        ];
    }

    public function map($post): array
    {
        // Map the columns to their values
        return [
            $post->id,
            $post->category_name,
            $post->shop_name,
            $post->name, // Assuming 'name' is a field in the Category model
            $post->item_color,
            $post->item_size,
            $post->item_code,
            $post->description,
            $post->content,
            $post->price,
            $post->sell_price,
            $post->instock,
            $post->status,
            // Add other columns as needed
        ];
    }
}
