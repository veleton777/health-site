<?php

namespace App\Models\AppUser;

use App\Models\Article\AnswerVariant;
use App\Models\Article\Article;
use App\Services\Tokenizer\Tokenizer;
use Carbon\Carbon;
use DomainException;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $id
 * @property string $email
 * @property string $phone
 * @property string $temp_phone
 * @property string $city
 * @property string $status
 * @property string $phone_verify_token
 * @property Carbon $phone_verify_token_expire
 */
class User extends Authenticatable implements JWTSubject
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';

    protected $table = 'app_users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'temp_phone',
        'city',
        'status',
        'phone_verify_token',
        'phone_verify_token_expire'
    ];

    protected $casts = [
        'phone_verify_token_expire' => 'datetime',
    ];

    /**
     * @param Carbon $now
     * @return string
     * @throws Exception
     */
    public function requestPhoneVerification(Carbon $now): string
    {
        if (!empty($this->phone_verify_token) && $this->phone_verify_token_expire && $this->phone_verify_token_expire->gt($now)) {
            throw new DomainException('Код уже был отправлен!');
        }

        $this->phone_verify_token = Tokenizer::getRandomCode();
        $this->phone_verify_token_expire = $now->addMinutes(3);

        $this->save();

        return $this->phone_verify_token;
    }

    /**
     * @param string $code
     * @param Carbon $now
     */
    public function verifyPhone(string $code, Carbon $now): void
    {
        if ($code !== $this->phone_verify_token) {
            throw new DomainException('Неверный код подтверждения!');
        }

        if ($this->phone_verify_token_expire->lt($now)) {
            throw new DomainException('Время жизни кода истекло, запросите код еще раз!');
        }

        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->status = self::STATUS_ACTIVE;

        $this->save();
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * @return BelongsToMany
     */
    public function favoritesArticle(): BelongsToMany
    {
        return $this->belongsToMany(
            Article::class,
            'article_favorites',
            'user_id',
            'article_id'
        );
    }

    /**
     * @return BelongsToMany
     */
    public function likesArticle(): BelongsToMany
    {
        return $this->belongsToMany(
            Article::class,
            'article_likes',
            'user_id',
            'article_id'
        );
    }

    public function like(int $id): bool
    {
        if ($this->hasInLikes($id)) {
            $this->likesArticle()->detach($id);
            $like = false;
        } else {
            $this->likesArticle()->attach($id);
            $like = true;
        }

        return $like;
    }

    public function hasInLikes(int $id): bool
    {
        return $this->likesArticle()->where('id', $id)->exists();
    }

    /**
     * @return BelongsToMany
     */
    public function recommendationsArticle(): BelongsToMany
    {
        return $this->belongsToMany(
            Article::class,
            'article_recommendations',
            'user_id',
            'article_id'
        );
    }

    public function recommend(int $id): bool
    {
        if ($this->hasInRecommends($id)) {
            $this->recommendationsArticle()->detach($id);
            $recommend = false;
        } else {
            $this->recommendationsArticle()->attach($id);
            $recommend = true;
        }

        return $recommend;
    }

    public function hasInRecommends(int $id): bool
    {
        return $this->recommendationsArticle()->where('id', $id)->exists();
    }

    public function addArticleToFavorites(int $id): void
    {
        if ($this->hasInFavorites($id)) {
            throw new DomainException('Данная статья уже добавлена в избранное.');
        }
        $this->favoritesArticle()->attach($id);
    }

    public function hasInFavorites(int $id): bool
    {
        return $this->favoritesArticle()->where('id', $id)->exists();
    }

    public function removeArticleFromFavorites(int $id): void
    {
        $this->favoritesArticle()->detach($id);
    }

    /**
     * @return BelongsToMany
     */
    public function answerArticle(): BelongsToMany
    {
        return $this->belongsToMany(
            AnswerVariant::class,
            'user_article_answers',
            'user_id',
            'answer_id'
        );
    }

    public function hasInAnswers(int $questionId): bool
    {
        return $this
            ->answerArticle()
            ->where('question_id', $questionId)
            ->exists();
    }

    public function checkAnswer(int $questionId, int $answerId): void
    {
        if ($this->hasInAnswers($questionId)) {
            throw new DomainException('Вы уже отвечали на этот вопрос!');
        } else {
            $this->answerArticle()->attach($answerId, ['question_id' => $questionId]);
        }
    }
}
