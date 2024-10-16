<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Phone number is required.',
            'password.required' => 'Password is required.',
        ];
    }
}

