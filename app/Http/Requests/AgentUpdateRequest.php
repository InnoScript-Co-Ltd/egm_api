<?php

namespace App\Http\Requests;

use App\Enums\AgentStatusEnum;
use App\Enums\KycStatusEnum;
use App\Helpers\Enum;
use App\Models\Agent;
use App\Models\City;
use App\Models\Country;
use App\Models\RegionOrState;
use App\Models\Township;
use Illuminate\Foundation\Http\FormRequest;

class AgentUpdateRequest extends FormRequest
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
        $countriesId = implode(',', Country::pluck('id')->toArray());
        $regionOrStateId = implode(',', RegionOrState::pluck('id')->toArray());
        $citiesId = implode(',', City::pluck('id')->toArray());
        $townshipsId = implode(',', Township::pluck('id')->toArray());
        $agentStatus = implode(',', (new Enum(AgentStatusEnum::class))->values());
        $kycStatus = implode(',', (new Enum(KycStatusEnum::class))->values());

        $agentId = Agent::findOrFail(request('id'))->id;

        return [
            'profile' => 'nullable | file',
            'first_name' => 'nullable | string | min:3 | max:18',
            'last_name' => 'nullable | string | min:3 | max:18',
            'phone' => "nullable | string | unique:agents,phone,$agentId",
            'prefix' => 'nullable | string',
            'email' => "nullable | string | unique:agents,email,$agentId",
            'nrc_back' => 'nullable | file',
            'nrc_front' => 'nullable | file',
            'dob' => 'nullable | date',
            'nrc' => "nullable | string | unique:agents,nrc,$agentId",
            // "passport_front" => "nullable | file",
            // "passport_back" => "nullable | file",
            'country_id' => "nullable | in:$countriesId",
            'region_or_state_id' => "nullable | in:$regionOrStateId",
            'city_id' => "nullable | in:$citiesId",
            'township_id' => "nullable | in:$townshipsId",
            'address' => 'nullable | string',
            'password' => 'nullable | string | min:6 | max:18',
            'email_verified_at' => 'nullable | datetime',
            'phone_verified_at' => 'nullable | datetime',
            'status' => "nullable | in:$agentStatus",
            'kyc_status' => "nullable | in:$kycStatus",
        ];
    }
}
