<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-8">

        {{-- Back --}}
        <div class="mb-6">
            <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition">
                ← Back to tasks
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">

                <div class="flex items-start justify-between gap-4">

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Task
                        </p>

                        <h1 class="mt-1 text-xl font-semibold text-gray-900">
                            {{ $task->title }}
                        </h1>
                    </div>

                    <div class="flex items-center gap-3">

                        <a href="{{ route('tasks.edit', $task) }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Edit
                        </a>

                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                            onsubmit="return confirm('Delete this task?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                                Delete
                            </button>
                        </form>

                    </div>

                </div>

            </div>

            {{-- Content --}}
            <div class="p-6 space-y-8">

                {{-- Description --}}
                <div>

                    <h2 class="text-sm font-semibold text-gray-900">
                        Description
                    </h2>

                    <div class="mt-3 text-sm text-gray-600 leading-6">

                        @if ($task->description)
                            {!! nl2br(e($task->description)) !!}
                        @else
                            <span class="text-gray-400">
                                No description provided.
                            </span>
                        @endif

                    </div>

                </div>

                {{-- Task Information --}}
                <div class="border-t border-gray-100 pt-6">

                    <h2 class="text-sm font-semibold text-gray-900 mb-4">
                        Task Information
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        {{-- Assigned To --}}
                        <div>

                            <p class="text-xs text-gray-500">
                                Assigned To
                            </p>

                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ $task->assignedUser?->name ?? 'Unassigned' }}
                            </p>

                        </div>

                        {{-- Due Date --}}
                        <div>

                            <p class="text-xs text-gray-500">
                                Due Date
                            </p>

                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ $task->due_date?->format('M d, Y H:i') ?? 'No due date' }}
                            </p>

                        </div>

                        {{-- Priority --}}
                        <div>

                            <p class="text-xs text-gray-500">
                                Priority
                            </p>

                            @php
                                $priorityValue =
                                    $task->priority instanceof App\Enums\TaskPriority
                                        ? $task->priority->value
                                        : $task->priority;

                                $priorityClasses = match (strtolower($priorityValue ?? '')) {
                                    'high', 'urgent' => 'bg-red-50 text-red-700 border-red-100',
                                    'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                    'low' => 'bg-gray-100 text-gray-700 border-gray-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200',
                                };
                            @endphp

                            <span
                                class="inline-flex mt-1 px-2.5 py-1 rounded-md text-xs font-medium border {{ $priorityClasses }}">
                                {{ ucfirst($priorityValue ?? 'Unknown') }}
                            </span>

                        </div>

                        {{-- Status --}}
                        <div>

                            <p class="text-xs text-gray-500">
                                Status
                            </p>

                            @php
                                $statusValue =
                                    $task->status instanceof App\Enums\TaskStatus
                                        ? $task->status->value
                                        : $task->status;

                                $statusClasses = match (strtolower($statusValue ?? '')) {
                                    'completed', 'done' => 'text-green-700',
                                    'in_progress', 'doing' => 'text-blue-700',
                                    'pending', 'todo' => 'text-gray-700',
                                    default => 'text-blue-700',
                                };
                            @endphp

                            <span
                                class="inline-flex mt-1 px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClasses }}">
                                {{ ucfirst(str_replace('_', ' ', $statusValue ?? 'Unknown')) }}
                            </span>

                        </div>

                    </div>

                </div>

                {{-- Related To --}}
                <div class="border-t border-gray-100 pt-6">

                    <h2 class="text-sm font-semibold text-gray-900 mb-4">
                        Related To
                    </h2>

                    @if ($task->taskable)
                        @php
                            $relatedType = match ($task->taskable_type) {
                                'company' => 'Company',
                                'lead' => 'Lead',
                                'deal' => 'Deal',
                                default => class_basename($task->taskable_type),
                            };

                            $relatedName = $task->taskable->name ?? ($task->taskable->title ?? 'Unnamed');
                        @endphp

                        <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">

                            <p class="text-xs text-gray-500">
                                {{ $relatedType }}
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-900">
                                {{ $relatedName }}
                            </p>

                        </div>
                    @else
                        <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">

                            <p class="text-sm text-gray-400">
                                This is a general task.
                            </p>

                        </div>
                    @endif

                </div>

                {{-- Timestamps --}}
                <div class="border-t border-gray-100 pt-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        {{-- Created --}}
                        <div>

                            <p class="text-xs text-gray-500">
                                Created
                            </p>

                            <p class="mt-1 text-sm text-gray-700">
                                {{ $task->created_at?->format('M d, Y H:i') }}
                            </p>

                        </div>

                        {{-- Updated --}}
                        <div>

                            <p class="text-xs text-gray-500">
                                Last Updated
                            </p>

                            <p class="mt-1 text-sm text-gray-700">
                                {{ $task->updated_at?->format('M d, Y H:i') }}
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
