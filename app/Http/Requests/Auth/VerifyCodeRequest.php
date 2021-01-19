<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\AbstractApplicationRequest;

class VerifyCodeRequest extends AbstractApplicationRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => 'required|digits_between:10,12',
            'code' => 'required|digits:4',
        ];
    }
}
