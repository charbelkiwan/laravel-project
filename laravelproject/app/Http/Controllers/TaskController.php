<?php

namespace App\Http\Controllers;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Task;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = QueryBuilder::for(Task::class)
            ->with(['user', 'project'])
            ->allowedFilters('title')
            ->allowedSorts('created_at', 'title', 'id')
            ->defaultSort('-created_at')
            ->paginate(request('per_page', 10))
            ->appends(request()->query());

        return response(['success' => true, 'data' => $tasks]);
    }

    public function show(Task $task)
    {
        $task->load('user', 'project');
        return response(['success' => true, 'data' => $task]);
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'status' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $task = Task::create($validated_data);
        return response(['success' => true, 'data' => $task]);
    }

    public function update(Request $request, Task $task)
    {
        $validated_data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'status' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $task->update($validated_data);
        return response(['success' => true, 'data' => $task]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response(['data' => $task], Response::HTTP_NO_CONTENT);
    }
}
