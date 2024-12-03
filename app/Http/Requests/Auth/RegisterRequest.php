<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'same:confirmation_password'],
            'confirmation_password' => ['required'],
            'email' => ['required', 'email'],
            'name' => ['required'],
        ];
    }
}
