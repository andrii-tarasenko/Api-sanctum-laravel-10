<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Domain\User\Services\AuthService;
use Domain\User\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    private $userService;

    private $authService;

    /**
     * @param UserService $userService
     * @param AuthService $authService
     */
    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    /**
     * Registration new user.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, $validator->errors());
        }

        return $this->sendResponse($this->userService->registration($request->all()), 'User was created successfully.');
    }

    /**
     * Authorisation user in the system.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, $validator->errors());
        }

        $authService = $this->authService->login($request['email'], $request['password']);

        if ($authService === null) {
            return $this->sendError(401, 'Unauthorized');
        }

        return $this->sendResponse($authService, 'User logged in successfully.');
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        if ($this->authService->logout($request->bearerToken())) {
            return $this->sendResponse(null, 'User logged out successfully.');
        }

        return $this->sendError(422, 'Something went wrong.');
    }
}
