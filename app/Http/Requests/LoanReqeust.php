<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanReqeust extends FormRequest
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
            "price" => '',
            "national_code" => '',
            "loan_id" => '',
            "guarantor_accept" => '',
            "type" => '',
            "admin_accept" => '',
            "admin_description" => "",
            "loan_price" => '',
            "installment_count" => '',
            "guarantors_id" => '',
            "last_guarantor_id" => '',
            "new_guarantor_id" => '',
            "user_id" => ' ',
            'user_descirption' => '',
        ];
    }
}
