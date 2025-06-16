<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function index()
    {
        try {
            $tasks = Task::all();
            
            return response()->json([
                'message' => 'Tasks retrieved successfully.',
                'tasks' => $tasks
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve tasks.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => "required|unique:tasks",
                'status' => "string",
                'description' => "max:100"
            ]);

            $task = Task::create($data);

            return response()->json([
                'message' => 'Tasks create successfully.',
                'task' => $task
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $task = Task::findOrFail($id);
            
            return response()->json([
                'message' => 'Task retrieved successfully.',
                'task' => $task
            ], 201);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found.'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            
            $data = $request->validate([
                'title' => "unique:tasks,title,{$id}",
                'status' => "string",
                'description' => "max:100"
            ]);

            $task->update($data);

            return response()->json([
                'message' => 'Tasks update successfully.',
                'task' => $task
            ], 201);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found.'
            ], 404);
            
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json([
                'message' => 'Tasks delete successfully.'
            ], 201);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found.'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
