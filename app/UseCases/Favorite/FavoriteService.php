<?php


namespace App\UseCases\Favorite;


use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Models\Article\Article;
use App\UseCases\AppUser\UserService;
use App\UseCases\Article\ArticleService;
use Illuminate\Support\Collection;

class FavoriteService
{
    private $userService;
    private $articleService;

    public function __construct(UserService $userService, ArticleService $articleService)
    {
        $this->userService = $userService;
        $this->articleService = $articleService;
    }

    /**
     * @param int $userId
     * @param int $articleId
     * @throws EntityNotFoundException
     */
    public function add(int $userId, int $articleId): void
    {
        $user = $this->userService->getUserById($userId);

        $this->articleService->getById($articleId);

        $user->addArticleToFavorites($articleId);
    }

    /**
     * @param int $userId
     * @param array $params
     * @return array
     */
    public function findByParams(int $userId, array $params): array
    {
        $qb = Article::query();
        $qb->select('articles.*');

        $qb->join(
            'article_favorites',
            'articles.id',
            '=',
            'article_favorites.article_id'
        );

        $qb->where('article_favorites.user_id', $userId);

        $qb->orderByDesc('created_at');

        $count = $qb->count();

        if ($limit = $params['limit'] ?? null) {
            if ($page = $params['page'] ?? null) {
                $qb->offset(($page - 1) * $limit);
            }

            $qb->limit($limit);
        }

        $articles = $qb->get();

        $likes = null;
        $recommends = null;
        $articlesId = [];

        foreach ($articles as $item) {
            $articlesId[] = $item['id'];
        }

        $likes = $this->getLikes($articlesId, $userId);
        $recommends = $this->getRecommends($articlesId, $userId);

        return [
            'articles' => $articles,
            'likes' => $likes,
            'recommends' => $recommends,
            'count' => $count,
        ];
    }

    /**
     * @param int $userId
     * @param int $articleId
     * @throws EntityNotFoundException
     */
    public function delete(int $userId, int $articleId): void
    {
        $user = $this->userService->getUserById($userId);

        $this->articleService->getById($articleId);

        $user->removeArticleFromFavorites($articleId);
    }

    /**
     * @param array $articleIds
     * @param int $userId
     * @return Collection
     */
    private function getLikes(array $articleIds, int $userId): Collection
    {
        $qb = Article::query();
        $qb->select('article_likes.article_id');

        $qb->rightJoin(
            'article_likes',
            'articles.id',
            '=',
            'article_likes.article_id'
        );

        $qb->whereIn('article_likes.article_id', $articleIds);
        $qb->where('article_likes.user_id', $userId);

        return $qb->get();
    }

    /**
     * @param array $articleIds
     * @param int $userId
     * @return Collection
     */
    private function getRecommends(array $articleIds, int $userId): Collection
    {
        $qb = Article::query();
        $qb->select('article_recommendations.article_id');

        $qb->rightJoin(
            'article_recommendations',
            'articles.id',
            '=',
            'article_recommendations.article_id'
        );

        $qb->whereIn('article_recommendations.article_id', $articleIds);
        $qb->where('article_recommendations.user_id', $userId);

        return $qb->get();
    }
}
