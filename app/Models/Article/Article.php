<?php

namespace App\Models\Article;

use App\Models\AppUser\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $preview_text
 * @property string $preview_image
 * @property int $count_likes
 * @property int $count_recommends
 */
class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'title', 'content', 'preview_text', 'preview_image', 'count_likes', 'count_recommends'
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'article_favorites',
            'article_id',
            'user_id'
        );
    }

    /**
     * @return HasOne
     */
    public function questionnaire(): HasOne
    {
        return $this->hasOne(Questionnaire::class);
    }
}
