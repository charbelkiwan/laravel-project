<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with('users', 'tasks');

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('sort') && $request->sort === 'created') {
            $query->orderBy('created_at');
        }
        $perPage = 10;
        $projects = $query->paginate($perPage);
        return response(['success' => true, 'data' => $projects]);
    }

    public function show(Project $project)
    {
        $project_with_relations = $project->load('users', 'tasks');
        return response(['success' => true, 'data' => $project_with_relations]);
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::create($validated_data);
        return response(['success' => true, 'data' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $project->update($validated_data);
        return response(['success' => true, 'data' => $project]);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response(['data' => $project], Response::HTTP_NO_CONTENT);
    }
}
