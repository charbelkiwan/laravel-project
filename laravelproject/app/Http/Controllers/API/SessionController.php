<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{

    public function store(Request $request)
    {
        $attributes = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($attributes)) {
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

    public function destroy()
    {
        Auth::user()->tokens->each(function ($token) {
            $token->delete();
        });
        return response('Successfully logged out', Response::HTTP_FORBIDDEN);
    }
}
