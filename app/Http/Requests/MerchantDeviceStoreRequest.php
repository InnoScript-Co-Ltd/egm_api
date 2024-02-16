<?php

namespace App\Http\Requests;

use App\Enums\UserTypeEnum;
use App\Helpers\Enum;
use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class MerchantDeviceStoreRequest extends FormRequest
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
        $adminIds = implode(',', Admin::pluck('id')->toArray());
        $userTypeEnums = implode(',', (new Enum(UserTypeEnum::class))->values());

        return [
            'user_type' => "required | in:$userTypeEnums",
            'user_id' => "required | in:$adminIds",
            'men_used' => 'nullable | numeric',
            'disk_free' => 'nullable | numeric',
            'free_disk_total' => 'nullable | numeric',
            'real_disk_free' => 'nullable | numeric',
            'real_disk_total' => 'nullable | numeric',
            'model' => 'nullable | string',
            'operation_system' => 'nullable | string',
            'os_version' => 'nullable | string',
            'android_sdk_version' => 'nullable | string',
            'platform' => 'nullable | string',
            'manufacture' => 'nullable | string',
            'brand_name' => 'nullable | string',
            'web_version' => 'nullable | string',
            'deviced_id' => 'nullable | string',
            'device_language' => 'nullable | string',
        ];
    }
}
