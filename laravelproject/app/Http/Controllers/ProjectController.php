<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return response(['success' => true, 'data' => $projects]);
    }

    public function show(Project $project)
    {
        return response(['success' => true, 'data' => $project]);
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
        $project->users()->attach($validated_data['user_id']);
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
        $project->users()->sync($validated_data['user_id']);
        return response(['success' => true, 'data' => $project]);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response(['success' => true, 'data' => $project]);
    }
}
