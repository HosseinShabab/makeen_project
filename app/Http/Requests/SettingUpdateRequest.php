<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'guarantors_count' => 'integer',
            'loans_count' => 'integer',
            'fund_name' => 'string|unique:settings,fund_name',
            'phone_number' => 'string|size:11|regex:/^[0-9]+$/',
            'card_number' => 'integer|digits:16',
            'subscription' => 'integer'
        ];
    }
}
