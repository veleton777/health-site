<?php

namespace App\Http\Controllers\Api\Rest\V1;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Exceptions\DomainExceptions\Security\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\UseCases\Auth\CheckAuthService;
use App\UseCases\Like\LikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class LikeController extends Controller
{
    private $checkAuthService;
    private $likeService;

    public function __construct(CheckAuthService $checkAuthService, LikeService $likeService)
    {
        $this->checkAuthService = $checkAuthService;
        $this->likeService = $likeService;
    }

    /**
     * @param int $articleId
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Throwable
     * @throws UnauthorizedException
     */
    public function like(int $articleId): JsonResponse
    {
        $userId = $this->checkAuthService->getAuthorizedUserIdXorException();

        try {
            $like = $this->likeService->like($userId, $articleId);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json([
            'status' => 'success',
            'like' => $like,
        ]);
    }
}
