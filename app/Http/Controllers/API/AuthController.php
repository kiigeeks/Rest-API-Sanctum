<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make(
                $input, [
                    'name' => 'required|string|max:100',
                    'email' => 'required|email:dns|max:50|unique:users',
                    'password' => 'required|min:5'
                ]);

            if ($validator->fails()) {
                return $this->sendError(
                    $validator->errors(),
                    "Validation Error",
                    422);
            }

            $input['password'] = Hash::make($request->password);

            User::create($input);

            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            $data = [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ];

            return $this->sendResponse(
                $data,
                "Success Create User",
                201
            );

        } catch (Exception $error) {
            return $this->sendError(
                $error,
                "Something Wrong",
                400
            );
        }
    }

    public function login(Request $request)
    {
        try {
            $validateData = $request->validate([
                'email' => 'required|email:dns|max:50',
                'password' => 'required|min:5'
            ]);

            if (Auth::attempt($validateData)) {
                $user = User::where('email', $request->email)->first();
                $tokenResult = $user->createToken('authToken')->plainTextToken;

                $result = new UserResource($user);
                $data = [
                    'acces_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $result
                ];

                return $this->sendResponse($data, 'Successfull Login',  200);
            }else{
                return $this->sendError('Unauthorized', 'Failed Login', 500);
            }
        } catch (Exception $error) {
            return $this->sendError(
                $error,
                "Something Wrong",
                400
            );
        }
    }

    public function show(User $user)
    {
        try{
            $data = Auth::user($user);
            $result = new UserResource($data);
            return $this->sendResponse($result , 'Successfull Get User', 200);
        } catch (Exception $error) {
            return $this->sendError(
                $error,
                "Something Wrong",
                400
            );
        }
    }

    public function logout()
    {
        $data = User::find(Auth::user()->id);

        $result = new UserResource($data);

        $data->tokens()->delete();

        return $this->sendResponse($result, 'Successfull Logout', 200);
    }
}
