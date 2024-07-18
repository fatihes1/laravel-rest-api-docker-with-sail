<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authentication\AuthLoginRequest;
use App\Http\Requests\Authentication\AuthRegisterRequest;
use App\Http\Resources\Authentication\AuthBaseResource;
use App\Http\Resources\Authentication\AuthLoginResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        $request->validated();

        $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password),
        ]);

        return AuthBaseResource::make(['data' => $user, 'message' => 'User created successfully']);
    }

    public function login(AuthLoginRequest $request)
    {
        $request->validated();

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return AuthLoginResource::make(['token' => $token, 'user' => $user, 'message' => 'User logged in successfully']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return AuthBaseResource::make(['data' => null ,'message' => 'Successfully logged out']);
    }
}
