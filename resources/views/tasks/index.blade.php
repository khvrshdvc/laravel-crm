<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tasks</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Track and manage all your tasks across companies, leads, and deals.
                </p>
            </div>

            @can('create', App\Models\Task::class)
                <a href="{{ route('tasks.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition shadow-sm">
                    + Add Task
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('tasks.index') }}" class="mb-6">
            <div class="flex flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..."
                    class="flex-1 min-w-[200px] rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-2.5 border">

                <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-2.5 border">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>In progress</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                </select>

                <select name="priority" onchange="this.form.submit()"
                    class="rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-2.5 border">
                    <option value="">All priorities</option>
                    <option value="low" @selected(request('priority') === 'low')>Low</option>
                    <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
                    <option value="high" @selected(request('priority') === 'high')>High</option>
                </select>

                <select name="assigned_to" onchange="this.form.submit()"
                    class="rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-2.5 border">
                    <option value="">All users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(request('assigned_to') == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Search
                </button>

                @if (request('search') || request('status') || request('priority') || request('assigned_to'))
                    <a href="{{ route('tasks.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-medium text-gray-500">Task Title</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Related To</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Assigned To</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Due Date</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Priority</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Status</th>
                            <th class="px-6 py-4 font-medium text-gray-500 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('tasks.show', $task) }}"
                                        class="font-medium text-gray-900 hover:underline">
                                        {{ $task->title }}
                                    </a>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    @if ($task->taskable)
                                        @php
                                            $modelName = Illuminate\Support\Str::afterLast($task->taskable_type, '\\');
                                            $routeName =
                                                strtolower(Illuminate\Support\Str::plural($modelName)) . '.show';
                                            $title = $task->taskable->name ?? ($task->taskable->title ?? '—');
                                        @endphp

                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-md">
                                            <span class="text-gray-500 font-normal">
                                                {{ $modelName }}:
                                            </span>
                                            @if (\Illuminate\Support\Facades\Route::has($routeName))
                                                <a href="{{ route($routeName, $task->taskable) }}"
                                                    class="text-gray-900 font-semibold hover:underline">
                                                    {{ $title }}
                                                </a>
                                            @else
                                                <strong class="text-gray-900">{{ $title }}</strong>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $task->assignedUser?->name ?? 'Unassigned' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    @if ($task->due_date)
                                        @php
                                            $isOverdue =
                                                $task->due_date->isPast() &&
                                                ($task->status->value ?? $task->status) !== 'completed';
                                        @endphp
                                        <span class="{{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                                            {{ $task->due_date->format('Y-m-d') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $priorityValue = $task->priority->value ?? $task->priority;
                                        $priorityClasses = match (strtolower($priorityValue)) {
                                            'high', 'urgent' => 'bg-red-50 text-red-700 border-red-200',
                                            'medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'low' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            default => 'bg-gray-100 text-gray-800 border-gray-200',
                                        };
                                    @endphp
                                    <span
                                        class="px-2.5 py-0.5 rounded text-xs font-medium border {{ $priorityClasses }}">
                                        {{ ucfirst($priorityValue) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusValue = $task->status->value ?? $task->status;
                                        $statusClasses = match (strtolower($statusValue)) {
                                            'completed', 'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'in_progress', 'doing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'pending', 'todo' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            default => 'bg-blue-50 text-blue-700 border-blue-200',
                                        };
                                    @endphp
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClasses }}">
                                        {{ ucfirst(str_replace('_', ' ', $statusValue)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end items-center gap-3">
                                        @can('update', $task)
                                            <a href="{{ route('tasks.edit', $task) }}"
                                                class="text-gray-600 hover:text-gray-900 transition font-medium">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete', $task)
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                                onsubmit="return confirm('Delete this task?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 transition font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    @if (request('search') || request('status') || request('priority') || request('assigned_to'))
                                        <p class="text-gray-500">No tasks match your filters.</p>
                                    @else
                                        <p class="text-gray-500">No tasks found.</p>

                                        @can('create', App\Models\Task::class)
                                            <a href="{{ route('tasks.create') }}"
                                                class="inline-block mt-3 text-sm font-medium text-gray-900 hover:underline">
                                                Create your first task
                                            </a>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $tasks->links() }}
        </div>

    </div>
</x-app-layout>
