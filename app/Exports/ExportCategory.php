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
        return Category::select('id', 'title', 'level', 'description', 'status')->get();
    }

    public function headings(): array
    {
        return [
            'Id',
            'Title',
            'Level',   
            'Description',
            'Status'
        ];
    }

    public function map($post) : array
    {
        return [
            $post->id,
            $post->title,
            $post->level,
            $post->status,
            $post->description
        ];
    }
}
