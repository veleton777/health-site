<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\AbstractApplicationRequest;

class LoginRequest extends AbstractApplicationRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|exists:app_users,phone|digits_between:10,12',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.exists' => 'Пользователь с таким номером телефона не зарегистрирован!',
        ];
    }
}
