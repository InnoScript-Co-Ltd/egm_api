<?php

namespace App\Imports;

use App\Helpers\Snowflake;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportCategory implements ToModel, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $id = new Snowflake;

        return new Category([
            'id' => $id,
            'title' => $row['title'],
            'level' => $row['level'] === null ? 0 : $row['level'],
            'main_category_id' => null,
            'icon' => null,
            'description' => $row['description'],
            'status' => $row['status'],
        ]);
    }
}
