<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-8">

        <div class="mb-6">
            <a href="{{ route('tasks.index') }}"
                class="text-sm text-gray-500 hover:text-gray-900 transition flex items-center gap-1">
                &larr; Back to tasks
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                            Task Details
                        </p>
                        <h1 class="mt-1 text-xl font-semibold text-gray-900">
                            {{ $task->title }}
                        </h1>
                    </div>

                    <div class="flex items-center gap-3">
                        @can('update', $task)
                            <a href="{{ route('tasks.edit', $task) }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Edit
                            </a>
                        @endcan

                        @can('delete', $task)
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this task?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-8">

                <div>
                    <h2 class="text-sm font-semibold text-gray-900">
                        Description
                    </h2>
                    <div class="mt-3 text-sm text-gray-600 leading-6">
                        @if ($task->description)
                            {!! nl2br(e($task->description)) !!}
                        @else
                            <span class="text-gray-400 italic">
                                No description provided.
                            </span>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">
                        Task Information
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <div>
                            <p class="text-xs text-gray-500">Assigned To</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ $task->assignedUser?->name ?? 'Unassigned' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Due Date</p>
                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ $task->due_date?->format('M d, Y H:i') ?? 'No due date' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Priority</p>
                            @php
                                $priorityValue =
                                    $task->priority instanceof \BackedEnum ? $task->priority->value : $task->priority;

                                $priorityClasses = match (strtolower($priorityValue ?? '')) {
                                    'high', 'urgent' => 'bg-red-50 text-red-700 border-red-200',
                                    'medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'low' => 'bg-gray-100 text-gray-700 border-gray-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200',
                                };
                            @endphp

                            <span
                                class="inline-flex mt-1 px-2.5 py-1 rounded-md text-xs font-medium border {{ $priorityClasses }}">
                                {{ ucfirst($priorityValue ?? 'Unknown') }}
                            </span>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Status</p>
                            @php
                                $statusValue =
                                    $task->status instanceof \BackedEnum ? $task->status->value : $task->status;

                                $statusClasses = match (strtolower($statusValue ?? '')) {
                                    'completed', 'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'in_progress', 'doing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'pending', 'todo' => 'bg-gray-100 text-gray-700 border-gray-200',
                                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                                };
                            @endphp

                            <span
                                class="inline-flex mt-1 px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClasses }}">
                                {{ ucfirst(str_replace('_', ' ', $statusValue ?? 'Unknown')) }}
                            </span>
                        </div>

                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">
                        Related To
                    </h2>

                    @if ($task->taskable)
                        @php
                            $taskableClass = strtolower(class_basename($task->taskable_type));

                            $relatedType = match ($taskableClass) {
                                'company' => 'Company',
                                'lead' => 'Lead',
                                'deal' => 'Deal',
                                'contact' => 'Contact',
                                default => ucfirst($taskableClass),
                            };

                            $relatedName =
                                $task->taskable->name ??
                                ($task->taskable->title ??
                                    $task->taskable->first_name . ' ' . $task->taskable->last_name);

                            $relatedRoute = match ($taskableClass) {
                                'company' => route('companies.show', $task->taskable),
                                'lead' => route('leads.show', $task->taskable),
                                'deal' => route('deals.show', $task->taskable),
                                'contact' => route('contacts.show', $task->taskable),
                                default => null,
                            };
                        @endphp

                        <div
                            class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">
                                    {{ $relatedType }}
                                </p>
                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ $relatedName }}
                                </p>
                            </div>

                            @if ($relatedRoute)
                                <a href="{{ $relatedRoute }}"
                                    class="text-sm text-blue-600 hover:underline font-medium">
                                    View details &rarr;
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/50">
                            <p class="text-sm text-gray-500 italic">
                                This is a general task not linked to any entity.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500">Created</p>
                            <p class="mt-1 text-sm text-gray-700">
                                {{ $task->created_at?->format('M d, Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Last Updated</p>
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
