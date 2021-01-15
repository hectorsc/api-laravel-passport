<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use App\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|max:55',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors(), 'Validation Error']);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $accessToken = $user->createToken('authToken')->accessToken;
        return response(['user' => $user, 'access_token' => $accessToken]);

    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(!auth()->attempt($loginData)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')]
            ]);
            // return response(['message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }

    public function logout ()
    {
        $token = auth('api')->user()->token();
        $token->revoke();
        return response(['message' =>'You have been successfully logged out!'], 200);
    }

    public function userToken()
    {
        return response([
            'token' => auth('api')->user()->token()->id,
            'user_name'  => auth('api')->user()->name,
            'user_email' => auth('api')->user()->email
        ]);
    }

}
