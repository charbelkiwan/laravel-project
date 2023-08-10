<?php

namespace App\Http\Controllers;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = QueryBuilder::for(User::class)
            ->with(['projects', 'tasks'])
            ->allowedFilters('name')
            ->allowedSorts('created_at', 'id', 'name')
            ->defaultSort('-created_at')
            ->paginate(request('per_page', 10))
            ->appends(request()->query());

        return response(['success' => true, 'data' => $users]);
    }


    public function show(User $user)
    {
        $user->load('projects', 'tasks');
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
