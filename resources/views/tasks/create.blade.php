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
                    Create New Task
                </h2>
            </div>

            <form action="{{ route('tasks.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Task Title
                        <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="title" id="title" value="{{ old('title') }}"
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
                        class="mt-2 block w-full px-4 py-2.5 rounded-lg border
                    @error('description') border-red-500 @else border-gray-300 @enderror
                    focus:border-gray-900 focus:outline-none">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="border-t border-gray-100 pt-6">

                    <h3 class="text-sm font-semibold text-gray-900 mb-4">
                        Related To
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="taskable_type" class="block text-sm font-medium text-gray-700">
                                Related Type
                            </label>

                            <select name="taskable_type" id="taskable_type"
                                class="mt-2 block w-full px-4 py-2.5 rounded-lg border
                            @error('taskable_type') border-red-500 @else border-gray-300 @enderror
                            bg-white focus:border-gray-900 focus:outline-none">
                                <option value="">None</option>

                                <option value="company" @selected(old('taskable_type') === 'company')>
                                    Company
                                </option>

                                <option value="lead" @selected(old('taskable_type') === 'lead')>
                                    Lead
                                </option>

                                <option value="deal" @selected(old('taskable_type') === 'deal')>
                                    Deal
                                </option>
                            </select>

                            @error('taskable_type')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <div id="company-select" class="hidden">
                                <label for="company_id" class="block text-sm font-medium text-gray-700">
                                    Company
                                </label>

                                <select name="taskable_id" id="company_id" data-type="company"
                                    class="related-select mt-2 block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white"
                                    disabled>
                                    <option value="">Select Company</option>

                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" @selected(old('taskable_type') === 'company' && old('taskable_id') == $company->id)>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div id="lead-select" class="hidden">

                                <label for="lead_id" class="block text-sm font-medium text-gray-700">
                                    Lead
                                </label>

                                <select name="taskable_id" id="lead_id" data-type="lead"
                                    class="related-select mt-2 block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white"
                                    disabled>
                                    <option value="">Select Lead</option>

                                    @foreach ($leads as $lead)
                                        <option value="{{ $lead->id }}" @selected(old('taskable_type') === 'lead' && old('taskable_id') == $lead->id)>
                                            {{ $lead->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div id="deal-select" class="hidden">

                                <label for="deal_id" class="block text-sm font-medium text-gray-700">
                                    Deal
                                </label>

                                <select name="taskable_id" id="deal_id" data-type="deal"
                                    class="related-select mt-2 block w-full px-4 py-2.5 rounded-lg border border-gray-300 bg-white"
                                    disabled>
                                    <option value="">Select Deal</option>

                                    @foreach ($deals as $deal)
                                        <option value="{{ $deal->id }}" @selected(old('taskable_type') === 'deal' && old('taskable_id') == $deal->id)>
                                            {{ $deal->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div id="no-related" class="mt-8 text-sm text-gray-400">
                                Select a type
                            </div>

                            @error('taskable_id')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700">
                            Assign To
                        </label>

                        <select name="assigned_to" id="assigned_to"
                            class="mt-2 block w-full px-4 py-2.5 rounded-lg border
                        @error('assigned_to') border-red-500 @else border-gray-300 @enderror
                        bg-white">
                            <option value="">
                                Select User
                            </option>

                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected(old('assigned_to') == $user->id)>
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

                        <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}"
                            class="mt-2 block w-full px-4 py-2.5 rounded-lg border
                        @error('due_date') border-red-500 @else border-gray-300 @enderror">

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
                            class="mt-2 block w-full px-4 py-2.5 rounded-lg border
                        @error('priority') border-red-500 @else border-gray-300 @enderror
                        bg-white">
                            @foreach (App\Enums\TaskPriority::cases() as $priority)
                                <option value="{{ $priority->value }}" @selected(old('priority') === $priority->value)>
                                    {{ ucfirst($priority->value) }}
                                </option>
                            @endforeach
                        </select>

                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Status
                            <span class="text-red-500">*</span>
                        </label>

                        <select name="status" id="status"
                            class="mt-2 block w-full px-4 py-2.5 rounded-lg border
                        @error('status') border-red-500 @else border-gray-300 @enderror
                        bg-white">
                            @foreach (App\Enums\TaskStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('status') === $status->value)>
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
                        Save Task
                    </button>

                </div>

            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const typeSelect = document.getElementById('taskable_type');

            const companySelect = document.getElementById('company-select');
            const leadSelect = document.getElementById('lead-select');
            const dealSelect = document.getElementById('deal-select');
            const noRelated = document.getElementById('no-related');

            const relatedSelects = document.querySelectorAll('.related-select');

            function updateRelatedSelect() {

                const type = typeSelect.value;

                companySelect.classList.add('hidden');
                leadSelect.classList.add('hidden');
                dealSelect.classList.add('hidden');
                noRelated.classList.add('hidden');

                relatedSelects.forEach(select => {
                    select.disabled = true;
                });

                if (type === 'company') {
                    companySelect.classList.remove('hidden');
                    document.getElementById('company_id').disabled = false;
                } else if (type === 'lead') {
                    leadSelect.classList.remove('hidden');
                    document.getElementById('lead_id').disabled = false;
                } else if (type === 'deal') {
                    dealSelect.classList.remove('hidden');
                    document.getElementById('deal_id').disabled = false;
                } else {
                    noRelated.classList.remove('hidden');
                }
            }

            typeSelect.addEventListener('change', updateRelatedSelect);

            updateRelatedSelect();
        });
    </script>
</x-app-layout>
