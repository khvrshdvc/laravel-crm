<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;

class TaskService
{
    // Create a new task and notify the assigned user
    public function create(array $data): Task
    {
        $task = Task::create($data);

        $this->notifyAssignedUser($task);

        DashboardCacheService::flush();

        return $task;
    }

    // Update an existing task and notify if assignment changed
    public function update(Task $task, array $data): Task
    {
        $oldAssignedTo = $task->assigned_to;

        $task->update($data);

        if ($task->assigned_to && $task->assigned_to !== $oldAssignedTo) {
            $this->notifyAssignedUser($task);
        }

        DashboardCacheService::flush();

        return $task->fresh();
    }

    // Delete a task
    public function delete(Task $task): void
    {
        $task->delete();

        DashboardCacheService::flush();
    }

    // Retrieve form options for create and edit views
    public function getFormDataOptions(): array
    {
        return [
            'users' => User::select('id', 'name')->orderBy('name')->get(),
            'companies' => Company::select('id', 'name')->orderBy('name')->get(),
            'leads' => Lead::select('id', 'name')->orderBy('name')->get(),
            'deals' => Deal::select('id', 'title as name')->orderBy('title')->get(),
        ];
    }

    // Notify the user assigned to the task
    protected function notifyAssignedUser(Task $task): void
    {
        if (! $task->assigned_to) {
            return;
        }

        $assignedUser = User::find($task->assigned_to);
        $assignedUser?->notify(new TaskAssigned($task));
    }
}
