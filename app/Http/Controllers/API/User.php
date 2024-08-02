<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User as UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class User extends BaseController
{
    final public const TOKEN_FIRST_ELEMENT_IN_ARRAY = 1;
    final public const TYPE_OF_TOKEN = 'Bearer token';

    /**
     * Registration new user
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = new UserModel();
        $user->fill($input);
        $user->save();
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Authorisation user in the system
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (Auth::attempt($loginData)) {
            $userAuth = Auth::user();
            $user = UserModel::all()->find($userAuth->getAuthIdentifier());
            $token = explode('|', $user->createToken('MyApp')->plainTextToken);
            $success['token'] =  $token[self::TOKEN_FIRST_ELEMENT_IN_ARRAY];
            $success['token_type'] =  self::TYPE_OF_TOKEN;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $accessToken = $request->bearerToken();
        $token = PersonalAccessToken::findToken($accessToken);

        if ($token->delete()) {
            return $this->sendResponse([], 'Successfully logged out.');
        }

        return $this->sendError('User is authorisation.', ['error' => 'Somthing went wrong.']);
    }
}
