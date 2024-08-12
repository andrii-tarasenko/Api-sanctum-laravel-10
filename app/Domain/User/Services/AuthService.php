<?php

namespace Domain\User\Services;

use Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    private $userRepository;

    final public const TOKEN_FIRST_ELEMENT_IN_ARRAY = 1;

    final public const TYPE_OF_TOKEN = 'Bearer token';

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return array|null
     */
    public function login(string $email, string $password): ?array
    {
        $user = $this->userRepository->getUserByEmail($email);

        if ($user && Auth::attempt(['email' => $email, 'password' => $password])) {
            $token = explode('|', $user->createToken('MyApp')->plainTextToken);

            return [
                'token' => $token[self::TOKEN_FIRST_ELEMENT_IN_ARRAY],
                'Authorization' => self::TYPE_OF_TOKEN,
            ];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function logout(string $token): bool
    {
        $token = PersonalAccessToken::findToken($token);

        return $token->delete();
    }
}
