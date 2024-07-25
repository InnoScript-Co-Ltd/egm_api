<?php

namespace App\Http\Requests\Agents;

use Illuminate\Foundation\Http\FormRequest;

class AgentChannelStoreRequest extends FormRequest
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
        return [
            'name' => 'required | string',
            'percentage_pattern' => 'required | string',
            'max_agent' => 'required | numeric',
            'percentage' => 'required | array',
        ];
    }

    public function messages(): array
    {
        return [
            'percentage.array' => 'Invalid percentage format',
        ];
    }
}
