<?php

namespace App\Http\Requests\Agents;

use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class AgentPackageRequestStoreRequest extends FormRequest
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
        $packageIds = implode(',', Package::pluck('id')->toArray());

        return [
            'package_id' => "required | in:$packageIds",
        ];
    }
}
