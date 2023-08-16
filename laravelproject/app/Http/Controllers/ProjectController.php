<?php

namespace App\Http\Controllers;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = QueryBuilder::for(Project::class)
            ->with(['users', 'tasks'])
            ->allowedFilters('title', AllowedFilter::exact('id'))
            ->allowedSorts('created_at', 'id', 'title')
            ->defaultSort('-created_at')
            ->paginate(request('per_page', 10))
            ->appends(request()->query());

        return response(['success' => true, 'data' => $projects]);
    }

    public function show(Project $project)
    {
        $project->load('users', 'tasks');
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
