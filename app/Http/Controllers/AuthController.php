<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Exception;

class AuthController extends Controller
{
    /**
     * Allows users to authenticate
     *
     * @param LoginRequest $request
     * @return void
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // Attempt to authenticate the user
        $auth = Auth::guard('web')->attempt($credentials);
        if(!$auth) {
            // Authentication failed
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // generate sacntum token
        $token = $request->user()->createToken('token', ['*'], now()->addMinutes(60));

        // Return a JSON response with the token
        return response()->json([
                'token' => $token->plainTextToken,
                'type' => 'bearer',
                'expires_in' => $token->accessToken->expires_at,
            ],
            200
        );
    }

    public function logout()
    {
        try {
            // Revoke the current user's token
            auth()->user()->tokens()->delete();
            return response()->json(['message' => 'Logout successful'], 200);
        } catch(Exception $e) {
            return response()->json(['message' => 'Unable to complete logout. Please try again later.'], 400);
        }
    }
}
