<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'string',
            'last_name' => 'string',
            'father_name'=>'string',
            'home_number' => 'string|size:11|regex:/^[0-9]+$/',
            'emergency_number' => 'string|size:11|regex:/^[0-9]+$/',
            'address' => 'string',
            'sheba_number' => 'string|size:24|regex:/^[0-9]+$/',
            'card_number' => 'string|size:16|regex:/^[0-9]+$/',
            'profile' => 'image|max:2048',
            'card' => 'image|max:2048'
        ];
    }
}
