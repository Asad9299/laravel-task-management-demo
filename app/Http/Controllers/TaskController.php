<?php 
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\User\TaskRequest;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->paginate(config('app.records_per_page'));

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = auth()->id();

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $this->authorizeTask($task);

        return view('tasks.edit', compact('task'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorizeTask($task);

        $validated = $request->validated();

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    private function authorizeTask(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
