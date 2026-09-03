<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Company;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Task::class);

        $tasks = Task::query()
            ->with([
                'assignedUser:id,name',
                'taskable' => function ($morphTo) {
                    $morphTo->morphWith([
                        Company::class,
                        Lead::class,
                        Deal::class,
                    ]);
                },
            ])
            ->when($request->search, fn($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($request->status, fn($query, $status) => $query->where('status', $status))
            ->when($request->priority, fn($query, $priority) => $query->where('priority', $priority))
            ->when($request->assigned_to, fn($query, $user) => $query->where('assigned_to', $user))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return TaskResource::collection($tasks);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load(['assignedUser', 'taskable']);

        return new TaskResource($task);
    }
}
