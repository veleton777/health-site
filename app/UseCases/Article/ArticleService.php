<?php

namespace App\UseCases\Article;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Models\Article\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ArticleService
{
    /**
     * @param array $params
     * @param int|null $userId
     * @return array
     */
    public function findArticlesByParams(array $params, ?int $userId): array
    {
        $qb = Article::query();

        if ($categoryId = $params['category_id'] ?? null) {
            $qb->where('category_id', $categoryId);
        }

        $qb->orderByDesc('created_at');

        $count = $qb->count();

        if ($limit = $params['limit'] ?? null) {
            if ($page = $params['page'] ?? null) {
                $qb->offset(($page - 1) * $limit);
            }

            $qb->limit($limit);
        }

        $articles = $qb->get();

        $favorites = null;
        $likes = null;
        $recommends = null;

        if ($userId) {
            $articlesId = [];

            foreach ($articles as $item) {
                $articlesId[] = $item['id'];
            }

            $favorites = $this->getFavorites($articlesId, $userId);
            $likes = $this->getLikes($articlesId, $userId);
            $recommends = $this->getRecommends($articlesId, $userId);
        }

        return [
            'articles' => $articles,
            'favorites' => $favorites,
            'likes' => $likes,
            'recommends' => $recommends,
            'count' => $count,
        ];
    }

    /**
     * @param array $params
     * @param int|null $userId
     * @return array
     * @throws EntityNotFoundException
     */
    public function getFirstByParams(array $params, ?int $userId): array
    {
        $article = Article::query()->get()
            ->where('category_id', $params['category_id'])
            ->first();

        if ($article === null) {
            throw new EntityNotFoundException('В данной категории нет статей!');
        }

        $favorite = $this->searchFavorite($article->id, $userId);
        $like = $this->searchLike($article->id, $userId);
        $recommend = $this->searchRecommend($article->id, $userId);

        return [
            'article' => $article,
            'favorite' => $favorite,
            'like' => $like,
            'recommend' => $recommend
        ];
    }

    /**
     * @param int $articleId
     * @param int|null $userId
     * @return array
     * @throws EntityNotFoundException
     */
    public function getArticleById(int $articleId, ?int $userId): array
    {
        $article = $this->getById($articleId);

        $favorite = $this->searchFavorite($article->id, $userId);
        $like = $this->searchLike($article->id, $userId);
        $recommend = $this->searchRecommend($article->id, $userId);

        return [
            'article' => $article,
            'favorite' => $favorite,
            'like' => $like,
            'recommend' => $recommend
        ];
    }

    /**
     * @param int $articleId
     * @return Article|Model
     * @throws EntityNotFoundException
     */
    public function getById(int $articleId): Article
    {
        $article = Article::query()->find($articleId);

        if ($article === null) {
            throw new EntityNotFoundException('Такой статьи не существует!');
        }

        return $article;
    }

    /**
     * @param int $articleId
     * @param int|null $userId
     * @return bool
     */
    private function searchFavorite(int $articleId, ?int $userId): bool
    {
        $favorite = false;

        if ($userId) {
            $favorite = Article::query()
                ->join(
                    'article_favorites',
                    'articles.id',
                    '=',
                    'article_favorites.article_id'
                )
                ->where('article_favorites.user_id', $userId)
                ->where('article_favorites.article_id', $articleId)
                ->count();

            if ($favorite) {
                $favorite = true;
            } else {
                $favorite = false;
            }
        }

        return $favorite;
    }

    /**
     * @param int $articleId
     * @param int|null $userId
     * @return bool
     */
    private function searchLike(int $articleId, ?int $userId): bool
    {
        $like = false;

        if ($userId) {
            $like = Article::query()
                ->join(
                    'article_likes',
                    'articles.id',
                    '=',
                    'article_likes.article_id'
                )
                ->where('article_likes.user_id', $userId)
                ->where('article_likes.article_id', $articleId)
                ->count();

            if ($like) {
                $like = true;
            } else {
                $like = false;
            }
        }

        return $like;
    }

    /**
     * @param int $articleId
     * @param int|null $userId
     * @return bool
     */
    private function searchRecommend(int $articleId, ?int $userId): bool
    {
        $recommend = false;

        if ($userId) {
            $recommend = Article::query()
                ->join(
                    'article_recommendations',
                    'articles.id',
                    '=',
                    'article_recommendations.article_id'
                )
                ->where('article_recommendations.user_id', $userId)
                ->where('article_recommendations.article_id', $articleId)
                ->count();

            if ($recommend) {
                $recommend = true;
            } else {
                $recommend = false;
            }
        }

        return $recommend;
    }

    /**
     * @param array $articleIds
     * @param int $userId
     * @return Collection
     */
    private function getFavorites(array $articleIds, int $userId): Collection
    {
        $qb = Article::query();
        $qb->select('article_favorites.article_id');

        $qb->rightJoin(
            'article_favorites',
            'articles.id',
            '=',
            'article_favorites.article_id'
        );

        $qb->whereIn('article_favorites.article_id', $articleIds);
        $qb->where('article_favorites.user_id', $userId);

        return $qb->get();
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
