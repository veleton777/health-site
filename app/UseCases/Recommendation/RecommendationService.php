<?php

namespace App\UseCases\Recommendation;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Models\Article\Article;
use App\UseCases\AppUser\UserService;
use App\UseCases\Article\ArticleService;

class RecommendationService
{
    private $articleService;
    private $userService;

    public function __construct(ArticleService $articleService, UserService $userService)
    {
        $this->articleService = $articleService;
        $this->userService = $userService;
    }

    /**
     * @param int $userId
     * @param int $articleId
     * @return bool
     * @throws EntityNotFoundException
     */
    public function recommend(int $userId, int $articleId): bool
    {
        $user = $this->userService->getUserById($userId);

        $this->articleService->getById($articleId);

        $recommend = $user->recommend($articleId);

        /* @var Article $article */
        $article = Article::query()
            ->find($articleId);

        if ($recommend) {
            $article->count_recommends += 1;
        } else {
            $article->count_recommends -= 1;
        }

        $article->save();

        return $recommend;
    }
}
