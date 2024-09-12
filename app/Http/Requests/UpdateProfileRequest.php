<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'father_name'=>'required|string',
            'home_number' => 'required|string|size:11|regex:/^[0-9]+$/',
            'emergency_number' => 'required|string|size:11|regex:/^[0-9]+$/',
            'address' => 'required|array',
            'address.street' => 'required|string',
            'address.alley' => 'required|string',
            'address.city' => 'required|string',
            'address.plaque' => 'required|string',
            'address.description' => 'required|string',
            'sheba_number' => 'required|string|size:24|regex:/^[0-9]+$/',
            'card_number' => 'required|string|size:16|regex:/^[0-9]+$/',
            'profile' => 'required|image|max:2048',
            'card' => 'required|image|max:2048'
        ];
    }
}
