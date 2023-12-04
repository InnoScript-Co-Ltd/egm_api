<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportUser implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('name','email','phone','reward_point','coupons','status')->get();
    }

    public function headings():array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'reward_point',
            'coupons',
            'status'
        ];
    }
}
