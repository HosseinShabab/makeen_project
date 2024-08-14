<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
<<<<<<< HEAD
            "discription" => "required",
            "status" =>"required|in:read,unread"
=======
            "description" => "required",
>>>>>>> 3d60a1d31f0ef12fdf35ccc4412995b39d8fe7f4
        ];
    }
}
