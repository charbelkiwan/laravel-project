<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Task;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with('user', 'project');

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('sort') && $request->sort === 'created') {
            $query->orderBy('created_at');
        }
        $perPage = 10;
        $tasks = $query->paginate($perPage);
        return response(['success' => true, 'data' => $tasks]);
    }

    public function show(Task $task)
    {
        $task_with_relations = $task->load('user', 'project');
        return response(['success' => true, 'data' => $task_with_relations]);
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
        if ($task->trashed()) {
            $task->restore();
            return response(['success' => true, 'message' => 'Task restored'], Response::HTTP_ACCEPTED);
        }

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
