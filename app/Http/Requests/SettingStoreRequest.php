<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingStoreRequest extends FormRequest
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
            'guarantors_count' => 'required|integer',
            'loans_count' => 'required|integer',
            'fund_name' => 'required|string|unique:settings,fund_name',
            'phone_number' => 'required|string|size:11|regex:/^[0-9]+$/',
            'card_number' => 'required|integer|digits:16',
            'subscription' => 'required|integer'
        ];
    }
}
