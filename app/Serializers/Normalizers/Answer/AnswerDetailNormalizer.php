<?php


namespace App\Serializers\Normalizers\Answer;


use App\Models\Article\Question;

class AnswerDetailNormalizer
{
    /**
     * @param Question $question
     * @return array
     */
    public function normalize(Question $question): array
    {
        $normalized = $question->toArray();

        $normalized['answers'] = $question->answers()->get();

        return $normalized;
    }
}
