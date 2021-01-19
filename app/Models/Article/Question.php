<?php

namespace App\Models\Article;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property boolean $is_right_answer
 * @property int $questionnaire_id
 */
class Question extends Model
{
    public $timestamps = false;

    protected $table = 'article_questions';

    protected $fillable = [
        'title', 'is_right_answer', 'questionnaire_id'
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(AnswerVariant::class, 'question_id', 'id');
    }

    /**
     * @param int $answerId
     * @return AnswerVariant|Model|null
     */
    public function getAnswerById(int $answerId): ?AnswerVariant
    {
        return $this->answers()
            ->where('id', $answerId)
            ->first();
    }

    /**
     * @param int $id
     * @return Question|Model
     * @throws EntityNotFoundException
     */
    public static function getById(int $id): Question
    {
        $question = static::query()->find($id);

        if ($question === null) {
            throw new EntityNotFoundException('Такого вопроса не существует!');
        }

        return $question;
    }
}
