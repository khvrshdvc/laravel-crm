<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Company;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    // Retrieve paginated tasks with search and filters
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Task::class);

        $tasks = Task::query()
            ->with(['assignedUser:id,name', 'taskable'])
            ->when($request->search, fn($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($request->status, fn($query, $status) => $query->where('status', $status))
            ->when($request->priority, fn($query, $priority) => $query->where('priority', $priority))
            ->when($request->assigned_to, fn($query, $user) => $query->where('assigned_to', $user))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'users'));
    }
    // Display task details
    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task->load([
            'assignedUser:id,name',
            'taskable',
        ]);

        return view('tasks.show', compact('task'));
    }

    // Show form to create a new task
    public function create(): View
    {
        $this->authorize('create', Task::class);

        return view('tasks.create', $this->taskService->getFormDataOptions());
    }

    // Store a new task record
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->authorize('create', Task::class);

        $this->taskService->create($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    // Show form to edit an existing task
    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        $options = array_merge(['task' => $task], $this->taskService->getFormDataOptions());

        return view('tasks.edit', $options);
    }

    // Update task details
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $this->taskService->update($task, $request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    // Delete a task record
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->delete($task);

        return back()->with('success', 'Task deleted successfully.');
    }
}
