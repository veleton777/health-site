<?php

namespace App\Http\Requests\Article;

use App\Http\Requests\AbstractApplicationRequest;

class GetFirstArticleByParamsRequest extends AbstractApplicationRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|int|exists:article_categories,id',
        ];
    }
}
