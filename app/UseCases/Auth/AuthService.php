<?php


namespace App\UseCases\Auth;


use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Models\AppUser\User;
use App\Services\Sms\SmsSenderInterface;
use App\UseCases\AppUser\UserService;
use Carbon\Carbon;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    private $userService;
    private $smsService;

    public function __construct(UserService $userService, SmsSenderInterface $smsService)
    {
        $this->userService = $userService;
        $this->smsService = $smsService;
    }

    /**
     * @param array $params
     * @throws Exception
     */
    public function register(array $params)
    {
        $user = new User([
            'name' => $params['name'],
            'phone' => $params['phone'],
            'email' => $params['email'],
            'status' => User::STATUS_WAIT
        ]);

        $user->save();

        $code = $user->requestPhoneVerification(Carbon::now());

        $this->smsService->send($params['phone'], $code);
    }

    /**
     * @param array $params
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function login(array $params)
    {
        $phone = $params['phone'];

        $user = $this->userService->getUserByPhone($phone);

        $code = $user->requestPhoneVerification(Carbon::now());

        $this->smsService->send($phone, $code);
    }

    /**
     * @param array $params
     * @return string
     * @throws EntityNotFoundException
     */
    public function verifyPhone(array $params): string
    {
        $phone = $params['phone'];
        $code = $params['code'];

        $user = $this->userService->getUserByPhone($phone);
        $user->verifyPhone($code, Carbon::now());

        return $this->issueAuthTokenByUser($user);
    }

    /**
     * @param User $user
     * @return string
     */
    public function issueAuthTokenByUser(User $user): string
    {
        return sprintf("Bearer %s", JWTAuth::fromUser($user));
    }

    /**
     * @param array $params
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function requestPhoneVerification(array $params): void
    {
        $user = $this->userService->getUserByPhone($params['phone']);
        $code = $user->requestPhoneVerification(Carbon::now());

        $this->smsService->send($user->phone, $code);
    }
}
