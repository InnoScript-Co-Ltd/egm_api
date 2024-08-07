<?php

namespace App\Http\Requests\Agents;

use App\Enums\REGXEnum;
use App\Models\Agent;
use Illuminate\Foundation\Http\FormRequest;

class AgentAccountUpdateRequest extends FormRequest
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
        $agent = Agent::findOrFail(request('id'));
        $agentId = $agent->id;
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;

        return [
            'email' => "nullable | unique:agents,email,$agentId",
            'phone' => ['nullable', "unique:agents,phone,$agentId", "regex:$mobileRule"],
        ];
    }
}
