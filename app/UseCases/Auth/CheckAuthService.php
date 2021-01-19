<?php


namespace App\UseCases\Auth;


use App\Exceptions\DomainExceptions\Security\UnauthorizedException;
use App\Models\AppUser\User;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAuthService
{
    /**
     * @return int
     * @throws UnauthorizedException
     */
    public function getAuthorizedUserIdXorException(): int
    {
        return $this->getAuthorizedUserXorException()->id;
    }

    /**
     * @return int|null
     */
    public function getAuthorizedUserId(): ?int
    {
        try {
            return $this->getAuthorizedUserXorException()->id;
        } catch (UnauthorizedException $throwable) {
            return null;
        }
    }

    /**
     * @return User
     * @throws UnauthorizedException
     */
    public function getAuthorizedUserXorException(): User
    {
        try {
            /* @var User $user */
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user->isActive()) {
                throw UnauthorizedException::invalidCredentials();
            }

            return $user;
        } catch (Throwable $throwable) {
            throw UnauthorizedException::fromThrowable($throwable);
        }
    }
}
