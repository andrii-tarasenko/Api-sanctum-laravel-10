<?php

namespace Domain\User\Repositories;

use Domain\User\Models\User;

class UserRepository
{
    /**
     * @param string $email
     *
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::all()->where('email', $email)->first();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function createUser(User $user): bool
    {
        return $user->save();
    }
}
