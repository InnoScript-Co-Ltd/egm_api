<?php

namespace App\Http\Requests\Agents;

use Illuminate\Foundation\Http\FormRequest;

class AgentKycUpdateRequest extends FormRequest
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
        $agent = auth('agent')->user();
        $agentId = $agent->id;

        return [
            'nrc_front' => 'nullable | file',
            'nrc_back' => 'nullable | file',
            'nrc' => "nullable | unique:agents,nrc,$agentId",
            'dob' => 'nullable | date',
        ];
    }
}
