<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\User\TaskRequest;

class TaskApiController extends Controller
{
    /**
     * List tasks with pagination
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10); // default 10
            $tasks = Task::where('user_id', $request->user()->id)
                         ->orderBy('id', 'desc')
                         ->paginate($perPage);

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

    /**
     * Create new task
     */
    public function store(TaskRequest $request)
    {
        try {
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

    /**
     * Show single task
     */
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

    /**
     * Update task
     */
    public function update(TaskRequest $request, $id)
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

            $task->update($request->validated());

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

    /**
     * Delete task
     */
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
