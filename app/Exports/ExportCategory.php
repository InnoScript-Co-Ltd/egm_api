<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportCategory implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Category::select('id', 'title', 'icon', 'level', 'main_category_id', 'description', 'status')->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'title',
            'icon',
            'level',
            'main_category_id',
            'description',
            'status',
        ];
    }
}
