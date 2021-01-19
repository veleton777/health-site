<?php

namespace App\Http\Controllers\Api\Rest\V1;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Exceptions\DomainExceptions\Security\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\UseCases\Auth\CheckAuthService;
use App\UseCases\Recommendation\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class RecommendationController extends Controller
{
    private $checkAuthService;
    private $recommendationService;

    public function __construct(CheckAuthService $checkAuthService, RecommendationService $recommendationService)
    {
        $this->checkAuthService = $checkAuthService;
        $this->recommendationService = $recommendationService;
    }

    /**
     * @param int $articleId
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Throwable
     * @throws UnauthorizedException
     */
    public function recommend(int $articleId): JsonResponse
    {
        $userId = $this->checkAuthService->getAuthorizedUserIdXorException();

        try {
            $recommend = $this->recommendationService->recommend($userId, $articleId);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json([
            'status' => 'success',
            'recommend' => $recommend
        ]);
    }
}
