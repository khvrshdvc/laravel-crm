<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Company;
use App\Models\Deal;
use App\Models\Lead;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Laravel\Prompts\Task as PromptsTask;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Task::class);

        $tasks = Task::with(['assignedUser', 'taskable'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->priority, function ($query, $priority) {
                $query->where('priority', $priority);
            })
            ->when($request->assigned_to, function ($query, $user) {
                $query->where('assigned_to', $user);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $users = User::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'users'));
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['assignedUser', 'taskable']);

        return view('tasks.show', compact('task'));
    }

    public function create()
    {
        $this->authorize('create', Task::class);

        $users = User::select('id', 'name')->orderBy('name')->get();
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $leads = Lead::select('id', 'name')->orderBy('name')->get();
        $deals = Deal::select('id', 'title as name')->orderBy('title')->get();

        return view('tasks.create', compact('users', 'companies', 'leads', 'deals'));
    }

    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create', Task::class);

        Task::create($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $users = User::select('id', 'name')->orderBy('name')->get();
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $leads = Lead::select('id', 'name')->orderBy('name')->get();
        $deals = Deal::select('id', 'title as name')->orderBy('title')->get();

        return view('tasks.edit', compact('task', 'users', 'companies', 'leads', 'deals'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return back()->with('success', 'Task deleted successfully.');
    }
}
