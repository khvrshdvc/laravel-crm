<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- Header Section --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Tasks</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Track and manage all your tasks across companies, leads, and deals.
                </p>
            </div>

            <a href="{{ route('tasks.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition shadow-sm">
                + Add Task
            </a>
        </div>

        {{-- Flash Success Message --}}
        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search & Filters --}}
        <form method="GET" action="{{ route('tasks.index') }}" class="mb-6">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..."
                    class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-2.5 border">

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

                <button type="submit"
                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Search
                </button>
                @if (request('search') || request('status') || request('priority'))
                    <a href="{{ route('tasks.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Table Container --}}
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

                                {{-- Title --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-gray-900">
                                        {{ $task->title }}
                                    </span>
                                </td>

                                {{-- Polymorphic Relationship (Related To) --}}
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    @if ($task->taskable)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1  text-xs font-medium text-gray-700">
                                            <span class="text-gray-500 font-normal">
                                                {{ Illuminate\Support\Str::afterLast($task->taskable_type, '\\') }}:
                                            </span>
                                            <strong class="text-gray-900">
                                                {{ $task->taskable->name ?? ($task->taskable->title ?? '—') }}
                                            </strong>
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Assigned User --}}
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $task->assignedUser?->name ?? 'Unassigned' }}
                                </td>

                                {{-- Due Date --}}
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $task->due_date ? $task->due_date->format('Y-m-d') : '—' }}
                                </td>

                                {{-- Priority (Dynamic Colors) --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $priorityValue = $task->priority->value ?? $task->priority;
                                        $priorityClasses = match (strtolower($priorityValue)) {
                                            'high', 'urgent' => 'bg-red-50 text-red-700 border-red-100',
                                            'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                            'low' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            default => 'bg-gray-100 text-gray-800 border-gray-200',
                                        };
                                    @endphp
                                    <span
                                        class="px-2 py-0.5 rounded text-xs font-medium border {{ $priorityClasses }}">
                                        {{ ucfirst($priorityValue) }}
                                    </span>
                                </td>

                                {{-- Status (Dynamic Colors) --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusValue = $task->status->value ?? $task->status;
                                        $statusClasses = match (strtolower($statusValue)) {
                                            'completed', 'done' => 'bg-green-50 text-green-700 border-green-100',
                                            'in_progress', 'doing' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'pending', 'todo' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            default => 'bg-blue-50 text-blue-700 border-blue-100',
                                        };
                                    @endphp
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClasses }}">
                                        {{ ucfirst(str_replace('_', ' ', $statusValue)) }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end items-center gap-3">
                                        <a href="{{ route('tasks.edit', $task) }}"
                                            class="text-gray-600 hover:text-gray-900 transition font-medium">
                                            Edit
                                        </a>

                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                            onsubmit="return confirm('Delete this task?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 transition font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    @if (request('search') || request('status') || request('priority'))
                                        <p class="text-gray-500">No tasks match your filters.</p>
                                    @else
                                        <p class="text-gray-500">No tasks found.</p>

                                        <a href="{{ route('tasks.create') }}"
                                            class="inline-block mt-3 text-sm font-medium text-gray-900 hover:underline">
                                            Create your first task
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $tasks->links() }}
        </div>

    </div>
</x-app-layout>
