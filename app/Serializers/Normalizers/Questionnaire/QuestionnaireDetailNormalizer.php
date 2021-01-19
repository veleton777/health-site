<?php


namespace App\Serializers\Normalizers\Questionnaire;


use App\Models\Article\Question;
use App\Models\Article\Questionnaire;

class QuestionnaireDetailNormalizer
{
    /**
     * @param Questionnaire|null $questionnaire
     * @return array
     */
    public function normalize(?Questionnaire $questionnaire): array
    {
        if ($questionnaire === null) {
            return [];
        }

        $normalized = $questionnaire->toArray();

        $normalized['questions'] = [];

        /* @var Question $question */
        foreach ($questionnaire->questions()->get() ?? [] as $question) {
            $normalizedItem = $question->toArray();
            $normalizedItem['answers'] = $question->answers()->get();
            $normalized['questions'][] = $normalizedItem;
        }

        return $normalized;
    }
}
