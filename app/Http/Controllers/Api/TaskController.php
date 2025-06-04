<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response()->json([
            'message' => 'Tasks retrieved successfully.',
            'tasks' => $tasks
        ], 201);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => "required|unique:tasks",
            'status' => "string",
            'description' => "max:100"
        ]);

        $task = Task::create($data);

        return response()->json([
            'message' => 'Tasks create successfully.',
            'task' => $task->toArray()
        ], 201);

    }

    public function show(Task $task)
    {
        return response()->json([
            'message' => 'Task retrieved successfully.',
            'task' => $task->toArray()
        ], 201);
    }

    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title' => "unique:tasks,title,{$task->id}",
            'status' => "string",
            'description' => "max:100"
        ]);

        $task->fill($data);
        $task->save();

        return response()->json([
            'message' => 'Tasks update successfully.',
            'task' => $task->toArray()
        ], 201);

    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Tasks delete successfully.',
        ], 201);
    }
}
