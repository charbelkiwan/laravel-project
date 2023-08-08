<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response(['success' => true, 'data' => $tasks]);
    }

    public function show(Task $task)
    {
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
        $task->users()->attach($validated_data['user_id']);
        $task->projects()->attach($validated_data['project_id']);
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
        $task->users()->sync([$validated_data['user_id']]);
        $task->projects()->sync([$validated_data['project_id']]);
        return response(['success' => true, 'data' => $task]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response(['success' => true, 'data' => $task]);
    }
}
