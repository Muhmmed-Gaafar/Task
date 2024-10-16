<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $verification_code = rand(100000, 999999);
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'verification_code' => $verification_code,
        ]);
        $token = $user->createToken('Access Token')->plainTextToken;
        Log::info("Verification Code for user {$user->id}: $verification_code");
        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user,
            'token' => $token,
            'verification_code'=> $verification_code,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('phone', $request->phone)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The provided credentials are incorrect.'],
            ]);
        }
        if (!$user->is_verified) {
            return response()->json(['message' => 'Account not verified.'], 403);
        }
        return response()->json([
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ],200);
    }
    public function verifyCode(Request $request)
    {
        $request->validate(['verification_code' => 'required|digits:6']);
        $user = User::where('verification_code', $request->verification_code)->first();
        if (!$user) {
            return response()->json(['message' => 'Invalid verification code.'], 400);
        }
        $user->is_verified = true;
        $user->verification_code = null;
        $user->save();
        return response()->json([
            'message' => 'Account verified successfully.'
        ],200);
    }

}


