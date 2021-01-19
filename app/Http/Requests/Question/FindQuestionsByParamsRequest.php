<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\AbstractApplicationRequest;

class FindQuestionsByParamsRequest extends AbstractApplicationRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'article_id' => 'required|int|exists:articles,id'
        ];
    }
}
