<?php

namespace App\Http\Requests\Questionnaire;

use App\Http\Requests\AbstractApplicationRequest;

class CreateQuestionnaireRequest extends AbstractApplicationRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'questions' => 'array|present',
            'questions.*.question' => 'required|max:255',
            'questions.*.answers' => 'array|present',
            'questions.*.answers.*.title' => 'required|max:255',
            'questions.*.answers.*.is_right' => 'required|boolean',
        ];
    }
}
