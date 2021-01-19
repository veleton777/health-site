<?php

namespace App\Http\Controllers\Api\Rest\V1;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Exceptions\DomainExceptions\Security\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Favorite\FindFavoriteArticlesByParamsRequest;
use App\Serializers\Normalizers\Article\FavoriteArticlesListNormalizer;
use App\UseCases\Auth\CheckAuthService;
use App\UseCases\Favorite\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class FavoriteController extends Controller
{
    private $checkAuthService;
    private $favoriteService;
    private $favoriteArticlesListNormalizer;

    public function __construct(
        CheckAuthService $checkAuthService,
        FavoriteService $favoriteService,
        FavoriteArticlesListNormalizer $favoriteArticlesListNormalizer
    )
    {
        $this->checkAuthService = $checkAuthService;
        $this->favoriteService = $favoriteService;
        $this->favoriteArticlesListNormalizer = $favoriteArticlesListNormalizer;
    }

    /**
     * @param int $articleId
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Throwable
     * @throws UnauthorizedException
     */
    public function add(int $articleId): JsonResponse
    {
        $userId = $this->checkAuthService->getAuthorizedUserIdXorException();

        try {
            $this->favoriteService->add($userId, $articleId);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Статья добавлена в избранное!'
        ]);
    }

    /**
     * @param FindFavoriteArticlesByParamsRequest $request
     * @return JsonResponse
     * @throws UnauthorizedException
     */
    public function findByParams(FindFavoriteArticlesByParamsRequest $request): JsonResponse
    {
        $userId = $this->checkAuthService->getAuthorizedUserIdXorException();

        $params = $request->all();

        $articles = $this->favoriteService->findByParams($userId, $params);

        return response()->json(
            $this->favoriteArticlesListNormalizer->normalize($articles)
        );
    }

    /**
     * @param int $articleId
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Throwable
     * @throws UnauthorizedException
     */
    public function delete(int $articleId): JsonResponse
    {
        $userId = $this->checkAuthService->getAuthorizedUserIdXorException();

        try {
            $this->favoriteService->delete($userId, $articleId);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Статья удалена из избранного!'
        ]);
    }
}
