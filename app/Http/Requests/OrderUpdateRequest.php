<?php

namespace App\Http\Requests;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\REGXEnum;
use App\Helpers\Enum;
use App\Models\DeliveryAddress;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $deliverAddressId = implode(',', DeliveryAddress::all()->pluck('id')->toArray());
        $userId = implode(',', User::all()->pluck('id')->toArray());
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;
        $paymentTypeEnum = implode(',', (new Enum(PaymentTypeEnum::class))->values());
        $orderStatusEnum = implode(',', (new Enum(OrderStatusEnum::class))->values());

        return [
            'delivery_address_id' => "in:$deliverAddressId | required",
            'user_id' => "in:$userId | required",
            'user_name' => 'string | nullable',
            'phone' => ['nullable', 'string', "regex:$mobileRule"],
            'email' => "email | nullable",
            'delivery_address' => 'string | nullable',
            'delivery_contact_person' => 'string | nullable',
            'delivery_contact_phone' => ['nullable', 'string', "regex:$mobileRule"],
            'discount' => 'numeric | nullable',
            'delivery_feed' => 'numeric | nullable',
            'total_amount' => 'numeric | nullable',
            'items' => 'nullable',
            'payment_type' => "required | in:$paymentTypeEnum",
            'status' => "in:$orderStatusEnum | nullable",
        ];
    }
}
