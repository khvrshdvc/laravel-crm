<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-8">

        <div class="mb-6">
            <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition">
                ← Back to tasks
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                <h2 class="font-semibold text-gray-900 text-lg">
                    Edit Task
                </h2>
            </div>

            <form action="{{ route('tasks.update', $task) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Task Title
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}"
                        class="mt-2 block w-full px-4 py-2.5 rounded-lg border
                    @error('title') border-red-500 @else border-gray-300 @enderror
                    focus:border-gray-900 focus:outline-none">

                    @error('title')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description
                    </label>

                    <textarea name="description" id="description" rows="3"
                        class="mt-2 block w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-gray-900 focus:outline-none">{{ old('description', $task->description) }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="related_to" class="block text-sm font-medium text-gray-700">
                        Related To
                    </label>

                    @php
                        $currentType = old('taskable_type', $task->taskable_type);
                        $currentId = old('taskable_id', $task->taskable_id);

                        $selectedRelated = $currentType && $currentId ? $currentType . ':' . $currentId : '';
                    @endphp

                    <input type="hidden" name="taskable_type" id="taskable_type" value="{{ $currentType }}">

                    <input type="hidden" name="taskable_id" id="taskable_id" value="{{ $currentId }}">

                    <select id="related_to" onchange="updateTaskableValues(this)"
                        class="mt-2 block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white focus:border-gray-900 focus:outline-none">

                        <option value="">
                            None (General Task)
                        </option>

                        @if ($companies->count())
                            <optgroup label="Companies">

                                @foreach ($companies as $company)
                                    <option value="company:{{ $company->id }}" @selected($selectedRelated === 'company:' . $company->id)>
                                        {{ $company->name }}
                                    </option>
                                @endforeach

                            </optgroup>
                        @endif

                        @if ($leads->count())
                            <optgroup label="Leads">

                                @foreach ($leads as $lead)
                                    <option value="lead:{{ $lead->id }}" @selected($selectedRelated === 'lead:' . $lead->id)>
                                        {{ $lead->name }}
                                    </option>
                                @endforeach

                            </optgroup>
                        @endif

                        @if ($deals->count())
                            <optgroup label="Deals">

                                @foreach ($deals as $deal)
                                    <option value="deal:{{ $deal->id }}" @selected($selectedRelated === 'deal:' . $deal->id)>
                                        {{ $deal->name }}
                                    </option>
                                @endforeach

                            </optgroup>
                        @endif

                    </select>

                    @error('taskable_type')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('taskable_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700">
                            Assign To
                        </label>

                        <select name="assigned_to" id="assigned_to"
                            class="mt-2 block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white">
                            <option value="">
                                Select User
                            </option>

                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(old('assigned_to', $task->assigned_to) == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('assigned_to')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">
                            Due Date
                        </label>

                        <input type="datetime-local" name="due_date" id="due_date"
                            value="{{ old('due_date', $task->due_date?->format('Y-m-d\TH:i')) }}"
                            class="mt-2 block w-full px-4 py-2.5 rounded-lg border border-gray-300">

                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">
                            Priority
                            <span class="text-red-500">*</span>
                        </label>

                        <select name="priority" id="priority"
                            class="mt-2 block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white">

                            @foreach (App\Enums\TaskPriority::cases() as $priority)
                                <option value="{{ $priority->value }}" @selected(old('priority', $task->priority instanceof App\Enums\TaskPriority ? $task->priority->value : $task->priority) === $priority->value)>
                                    {{ ucfirst($priority->value) }}
                                </option>
                            @endforeach

                        </select>

                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Status
                            <span class="text-red-500">*</span>
                        </label>

                        <select name="status" id="status"
                            class="mt-2 block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white">

                            @foreach (App\Enums\TaskStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('status', $task->status instanceof App\Enums\TaskStatus ? $task->status->value : $task->status) === $status->value)>
                                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                </option>
                            @endforeach

                        </select>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">

                    <a href="{{ route('tasks.index') }}"
                        class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                        Update Task
                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>
        function updateTaskableValues(selectElement) {
            const value = selectElement.value;

            const typeInput = document.getElementById('taskable_type');
            const idInput = document.getElementById('taskable_id');

            if (!value) {
                typeInput.value = '';
                idInput.value = '';

                return;
            }

            const parts = value.split(':');

            typeInput.value = parts[0];
            idInput.value = parts[1];
        }
    </script>
</x-app-layout>
