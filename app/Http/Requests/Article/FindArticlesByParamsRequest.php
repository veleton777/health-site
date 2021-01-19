<?php

namespace App\Http\Requests\Article;

use App\Http\Requests\AbstractApplicationRequest;

class FindArticlesByParamsRequest extends AbstractApplicationRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'int|exists:article_categories,id',
            'page' => 'required|integer|min:1',
            'limit' => 'required|integer|min:1|max:1000'
        ];
    }
}
