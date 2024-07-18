<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\User as UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Laravel\Sanctum\PersonalAccessToken;

class User extends BaseController
{
    /**
     * Registration new user
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function register(Request $request)
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
        $user = UserModel::create($input);
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
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $token = explode('|', $user->createToken('MyApp')->plainTextToken);
            $success['token'] =  $token[1];
            $success['token_type'] =  'Bearer token';
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function logout(Request $request)
    {
        $accessToken = $request->bearerToken();
        $token = PersonalAccessToken::findToken($accessToken);

        if ($token->delete()) {
            return $this->sendResponse([], 'Successfully logged out.');
        }
    }
}
