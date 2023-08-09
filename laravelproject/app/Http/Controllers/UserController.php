<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('projects', 'tasks');
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('sort') && $request->sort === 'created') {
            $query->orderBy('created_at');
        }
        $perPage = 10;
        $users = $query->paginate($perPage);
        return response(['success' => true, 'data' => $users]);
    }

    public function show(User $user)
    {
        $user_with_relations = $user->load('projects', 'tasks');
        return response(['success' => true, 'data' => $user_with_relations]);
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

    public function update(Request $request, User $user)
    {
        $validated_data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user->update($validated_data);
        return response(['success' => true, 'data' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response(['data' => $user], Response::HTTP_NO_CONTENT);
    }
}
