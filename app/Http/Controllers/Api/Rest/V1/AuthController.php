<?php

namespace App\Http\Controllers\Api\Rest\V1;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Exceptions\DomainExceptions\Security\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyCodeRequest;
use App\UseCases\Auth\AuthService;
use App\UseCases\Auth\CheckAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class AuthController extends Controller
{
    private $authService;
    private $checkAuthService;

    public function __construct(AuthService $authService, CheckAuthService $checkAuthService)
    {
        $this->authService = $authService;
        $this->checkAuthService = $checkAuthService;
    }

    /**
     * @throws UnauthorizedException
     */
    public function getProfile(): JsonResponse
    {
        $user = $this->checkAuthService->getAuthorizedUserXorException();

        return response()->json(
            ['data' => $user]
        );
    }

    public function updateProfile()
    {

    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Throwable
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $params = $request->all();

        DB::beginTransaction();

        try {
            $this->authService->login($params);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Код подтверждения отправлен!'
        ]);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $params = $request->all();

        DB::beginTransaction();

        try {
            $this->authService->register($params);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Код подтверждения отправлен!'
        ]);
    }

    /**
     * @param VerifyCodeRequest $request
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Throwable
     */
    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        $params = $request->all();

        try {
            $token = $this->authService->verifyPhone($params);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json(
            $token
        );
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Throwable
     */
    public function requestPhoneVerification(LoginRequest $request): JsonResponse
    {
        $params = $request->all();

        try {
            $this->authService->requestPhoneVerification($params);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Код подтверждения отправлен!'
        ]);
    }
}
