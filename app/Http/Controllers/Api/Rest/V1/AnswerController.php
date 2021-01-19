<?php

namespace App\Http\Controllers\Api\Rest\V1;

use App\Http\Controllers\Controller;
use App\UseCases\Answer\AnswerService;
use App\UseCases\Auth\CheckAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class AnswerController extends Controller
{
    private $checkAuthService;
    private $answerService;

    public function __construct(
        CheckAuthService $checkAuthService,
        AnswerService $answerService,
    ) {
        $this->checkAuthService = $checkAuthService;
        $this->answerService = $answerService;
    }

    /**
     * @param int $answerId
     * @return JsonResponse
     * @throws Throwable
     */
    public function check(int $answerId): JsonResponse
    {
        $userId = $this->checkAuthService->getAuthorizedUserIdXorException();

        DB::beginTransaction();

        try {
            $this->answerService->check($answerId, $userId);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json([
            'status' => 'success',
            ''
        ]);
    }
}
