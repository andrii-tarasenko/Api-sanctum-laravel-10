<?php

namespace Domain\User\Services;

use Domain\User\Models\User;
use Domain\User\Repositories\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $inputData
     *
     * @return User|null
     */
    public function registration(array $inputData): ?User
    {
        $inputData['password'] = bcrypt($inputData['password']);
        $user = new User();
        $user->fill($inputData);

        if (!$this->userRepository->createUser($user)) {
            return null;
        }

        return $user;
    }
}
