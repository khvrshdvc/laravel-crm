<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Company;
use App\Models\Deal;
use App\Models\Lead;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['assignedUser', 'taskable'])
            ->latest()
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        $task->load(['assignedUser', 'taskable']);

        return view('tasks.show', compact('task'));
    }

    public function create()
    {
        $users = User::select('id', 'name')->orderBy('name')->get();
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $leads = Lead::select('id', 'name')->orderBy('name')->get();
        $deals = Deal::select('id', 'title as name')->orderBy('title')->get();

        return view('tasks.create', compact('users', 'companies', 'leads', 'deals'));
    }

    public function store(StoreTaskRequest $request)
    {
        Task::create($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $users = User::select('id', 'name')->orderBy('name')->get();
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $leads = Lead::select('id', 'name')->orderBy('name')->get();
        $deals = Deal::select('id', 'title as name')->orderBy('title')->get();

        return view('tasks.edit', compact('task', 'users', 'companies', 'leads', 'deals'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return back()->with('success', 'Task deleted successfully.');
    }
}
