<?php

namespace App\Exports;

use App\Models\Shop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportShop implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Shop::select(
            'id',
            'region_id',
            'name',
            'phone',
            'address',
            'location',
            'status'
        )->get();
    }

    public function headings():array
    {
        return [
            'Id',
            'Region Name',
            'Name',
            'Phone',
            'Address',
            'Location',
            'Status'
        ];
    }

    public function map($post): array
    {
        // Map the columns to their values
        return [
            $post->id,
            $post->region_name,
            $post->name, // Assuming 'name' is a field in the Category model
            $post->phone,
            $post->address,
            $post->location,
            $post->status
            // Add other columns as needed
        ];
    }
}
