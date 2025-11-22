<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TaskApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tasks = Task::where('user_id', $request->user()->id)
                         ->orderBy('id', 'desc')
                         ->paginate(config("app.records_per_page")); 
            return response()->json([
                'success' => true,
                'tasks'   => $tasks
            ]);
        } catch (\Exception $e) {
            Log::error('Task Index Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tasks.'
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'required|in:pending,in-progress,completed',
                'due_date'    => 'required|date|after_or_equal:today',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors()
                ], 422);
            }

            $task = Task::create([
                'user_id'     => $request->user()->id,
                'title'       => $request->title,
                'description' => $request->description,
                'status'      => $request->status,
                'due_date'    => $request->due_date,
            ]);

            return response()->json([
                'success' => true,
                'task'    => $task
            ]);
        } catch (\Exception $e) {
            Log::error('Task Store Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create task.'
            ], 400);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $task = Task::find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found'
                ], 404);
            }

            if ($task->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'task'    => $task
            ]);
        } catch (\Exception $e) {
            Log::error('Task Show Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch task.'
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $task = Task::find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found'
                ], 404);
            }

            if ($task->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'required|in:pending,in-progress,completed',
                'due_date'    => 'required|date|after_or_equal:today',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors()
                ], 422);
            }

            $task->update($validator->validated());

            return response()->json([
                'success' => true,
                'task'    => $task
            ]);
        } catch (\Exception $e) {
            Log::error('Task Update Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update task.'
            ], 400);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $task = Task::find($id);

            if (!$task) {
                return response()->json([
                    'success' => false,
                    'message' => 'Task not found'
                ], 404);
            }

            if ($task->user_id !== $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted'
            ]);
        } catch (\Exception $e) {
            Log::error('Task Delete Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task.'
            ], 400);
        }
    }
}
