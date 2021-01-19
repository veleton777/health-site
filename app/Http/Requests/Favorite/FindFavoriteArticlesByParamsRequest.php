<?php

namespace App\Http\Requests\Favorite;

use App\Http\Requests\AbstractApplicationRequest;

class FindFavoriteArticlesByParamsRequest extends AbstractApplicationRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'required|integer|min:1',
            'limit' => 'required|integer|min:1|max:1000'
        ];
    }
}
