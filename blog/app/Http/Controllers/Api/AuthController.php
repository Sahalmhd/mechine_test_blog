<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the login request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            // Generate an API token
            $token = $request->user()->createToken('API Token')->plainTextToken;

            // Return a success response with the token
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => $request->user(),
            ], 200);
        }

        // If authentication fails, return an error response
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }
}
