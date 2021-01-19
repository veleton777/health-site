<?php

namespace App\Models\Article;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $title
 * @property boolean $is_right
 * @property string $question_id
 * @property int $count_votes
 */
class AnswerVariant extends Model
{
    public $timestamps = false;

    protected $table = 'article_answer_variants';

    protected $fillable = [
        'title', 'is_right', 'question_id', 'count_votes'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    /**
     * @param int $id
     * @return AnswerVariant|Model
     * @throws EntityNotFoundException
     */
    public static function getById(int $id): AnswerVariant
    {
        $answer = static::query()->find($id);

        if ($answer === null) {
            throw new EntityNotFoundException('Такого варианта ответа не существует!');
        }

        return $answer;
    }
}
