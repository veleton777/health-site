<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\AbstractApplicationRequest;

class RegisterRequest extends AbstractApplicationRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'regex:/^[a-zA-Zа-яА-Я]+$/u', 'min:2', 'max:100'],
            'email' => 'required|unique:app_users,email|email:rfc,dns|max:80',
            'phone' => 'required|unique:app_users,phone|digits_between:10,12',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Пользователь с таким Email уже зарегистрирован!',
            'phone.unique' => 'Пользователь с таким номером телефона уже зарегистрирован!',
        ];
    }
}
