<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportOrder implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Order::select(
            'id',
            'delivery_address_id',
            'user_id',
            'user_name',
            'phone',
            'email',
            'delivery_address',
            'delivery_contact_person',
            'delivery_contact_phone',
            'discount',
            'delivery_feed',
            'total_amount',
            'payment_type',
            'status')->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'delivery_address_id',
            'user_id',
            'user_name',
            'phone',
            'email',
            'delivery_address',
            'delivery_contact_person',
            'delivery_contact_phone',
            'discount',
            'delivery_feed',
            'total_amount',
            'payment_type',
            'status',
        ];
    }
}
