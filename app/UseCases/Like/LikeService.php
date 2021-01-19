<?php


namespace App\UseCases\Like;


use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Models\Article\Article;
use App\UseCases\AppUser\UserService;
use App\UseCases\Article\ArticleService;

class LikeService
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
    public function like(int $userId, int $articleId): bool
    {
        $user = $this->userService->getUserById($userId);

        $this->articleService->getById($articleId);

        $like = $user->like($articleId);

        /* @var Article $article */
        $article = Article::query()
            ->find($articleId);

        if ($like) {
            $article->count_likes += 1;
        } else {
            $article->count_likes -= 1;
        }

        $article->save();

        return $like;
    }
}
