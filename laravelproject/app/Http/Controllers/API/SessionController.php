<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $rate_limited = RateLimiter::attempt(
            'login:'.$attributes['email'],
            2, 
            function () use ($attributes) {
                return auth()->attempt($attributes);
            },
            60
        );

        if (!$rate_limited) {
            $seconds_remaining = RateLimiter::availableIn('login:'.$attributes['email']);
            return response()->json([
                'message' => 'Too many login attempts. Please try again after ' . $seconds_remaining . ' seconds.'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        if (!$rate_limited || !auth()->attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => 'Your provided credentials could not be verified.'
            ]);
        }
        $user = Auth::user();
        $token = $user->createToken('token name')->plainTextToken;

        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();
        return response('Successfully logged out', Response::HTTP_FORBIDDEN);
    }
}
