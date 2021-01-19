<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $article_id
 */
class Questionnaire extends Model
{
    public $timestamps = false;

    protected $table = 'article_questionnaires';

    protected $fillable = [
        'article_id'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'questionnaire_id', 'id');
    }
}
