<?php

namespace App\Http\Controllers\Api\Rest\V1;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Article\FindArticlesByParamsRequest;
use App\Http\Requests\Article\GetFirstArticleByParamsRequest;
use App\Serializers\Normalizers\Article\ArticleDetailNormalizer;
use App\Serializers\Normalizers\Article\ArticlesListNormalizer;
use App\UseCases\Article\ArticleService;
use App\UseCases\Auth\CheckAuthService;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    private $articleService;
    private $articlesListNormalizer;
    private $articleDetailNormalizer;
    private $checkAuthService;

    public function __construct(
        ArticleService $articleService,
        ArticlesListNormalizer $articlesListNormalizer,
        ArticleDetailNormalizer $articleDetailNormalizer,
        CheckAuthService $checkAuthService
    ) {
        $this->articleService = $articleService;
        $this->articlesListNormalizer = $articlesListNormalizer;
        $this->articleDetailNormalizer = $articleDetailNormalizer;
        $this->checkAuthService = $checkAuthService;
    }

    /**
     * @param FindArticlesByParamsRequest $request
     * @return JsonResponse
     */
    public function findByParams(FindArticlesByParamsRequest $request): JsonResponse
    {
        $params = $request->all();

        $userId = $this->checkAuthService->getAuthorizedUserId();

        $articles = $this->articleService->findArticlesByParams($params, $userId);

        return response()->json(
            $this->articlesListNormalizer->normalize($articles)
        );
    }

    /**
     * @param int $articleId
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function getById(int $articleId): JsonResponse
    {
        $userId = $this->checkAuthService->getAuthorizedUserId();

        $article = $this->articleService->getArticleById($articleId, $userId);

        return response()->json(
            $this->articleDetailNormalizer->normalize($article)
        );
    }

    /**
     * @param GetFirstArticleByParamsRequest $request
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function getFirstByParams(GetFirstArticleByParamsRequest $request): JsonResponse
    {
        $userId = $this->checkAuthService->getAuthorizedUserId();

        $article = $this->articleService->getFirstByParams($request->all(), $userId);

        return response()->json(
            $this->articleDetailNormalizer->normalize($article)
        );
    }
}
