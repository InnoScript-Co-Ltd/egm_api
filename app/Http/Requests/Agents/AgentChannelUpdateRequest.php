<?php

namespace App\Http\Requests\Agents;

use Illuminate\Foundation\Http\FormRequest;

class AgentChannelUpdateRequest extends FormRequest
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
            'name' => 'nullable | string',
            'percentage_pattern' => 'nullable | string',
            'max_agent' => 'nullable | numeric',
            'percentage' => 'nullable | array',
        ];
    }

    public function messages(): array
    {
        return [
            'percentage.array' => 'Invalid percentage format',
        ];
    }
}
