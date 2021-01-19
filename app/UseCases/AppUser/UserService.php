<?php


namespace App\UseCases\AppUser;


use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Models\AppUser\User;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    /**
     * @param string $phone
     * @return User
     * @throws EntityNotFoundException
     */
    public function getUserByPhone(string $phone): User
    {
        $user = $this->findUserByPhone($phone);

        if ($user === null) {
            throw new EntityNotFoundException('Пользователя с таким телефоном не существует');
        }

        return $user;
    }

    /**
     * @param string $phone
     * @return User|Model|null
     */
    public function findUserByPhone(string $phone): ?User
    {
        return User::query()
            ->where('phone', $phone)
            ->first();
    }

    /**
     * @param int $id
     * @return User
     * @throws EntityNotFoundException
     */
    public function getUserById(int $id): User
    {
        $user = $this->findUserById($id);

        if ($user === null) {
            throw new EntityNotFoundException('Пользователь не найден!');
        }

        return $user;
    }

    /**
     * @param int $id
     * @return User|Model|null
     */
    public function findUserById(int $id): ?User
    {
        return User::query()->find($id);
    }
}
