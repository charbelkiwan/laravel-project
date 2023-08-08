<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response(['success' => true, 'data' => $users]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return response(['success' => true, 'data' => $user]);
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::create($validated_data);
        return response(['success' => true, 'data' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $validated_data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user->update($validated_data);
        return response(['success' => true, 'data' => $user]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();
        return response(['success' => true, 'data' => $user]);
    }
}
