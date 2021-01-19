<?php


namespace App\Serializers\Normalizers\Question;


use App\Models\Article\Question;
use Illuminate\Support\Collection;

class QuestionsListNormalizer
{
    /**
     * @param Collection $questions
     * @return array
     */
    public function normalize(Collection $questions): array
    {
        $normalized['data'] = [];

        /* @var Question $question */
        foreach ($questions as $question) {
            $normalizedItem = $question->toArray();

            $normalizedItem['answers'] = $question->answers()->get();
            $normalized['data'][] = $normalizedItem;
        }

        return $normalized;
    }
}
