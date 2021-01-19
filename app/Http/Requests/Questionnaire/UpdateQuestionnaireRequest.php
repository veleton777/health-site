<?php

namespace App\Http\Requests\Questionnaire;

use App\Http\Requests\AbstractApplicationRequest;

class UpdateQuestionnaireRequest extends AbstractApplicationRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'questions' => 'array|present',
            'questions.*.id' => 'int|exists:article_questions',
            'questions.*.question' => 'required|max:255',
            'questions.*.delete_flag' => 'required|boolean',
            'questions.*.answers' => 'array|present',
            'questions.*.answers.*.id' => 'int|exists:article_answer_variants',
            'questions.*.answers.*.title' => 'required|max:255',
            'questions.*.answers.*.is_right' => 'required|boolean',
            'questions.*.answers.*.delete_flag' => 'required|boolean',
        ];
    }
}
