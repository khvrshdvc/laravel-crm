<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-8">
        <div class="mb-8">
            <a href="{{ route('companies.index') }}"
                class="text-sm text-gray-500 hover:text-gray-900 transition flex items-center gap-1">
                &larr; Back to companies
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-start justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center border border-gray-200">
                    <span class="text-lg font-semibold text-gray-700">
                        {{ strtoupper(substr($company->name, 0, 1)) }}
                    </span>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ $company->name }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Company details & relations
                    </p>
                </div>
            </div>

            @can('update', $company)
                <a href="{{ route('companies.edit', $company) }}"
                    class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Edit company
                </a>
            @endcan
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <a href="{{ route('contacts.index', ['company_id' => $company->id]) }}"
                    class="text-sm font-medium text-gray-500 hover:text-blue-600 hover:underline transition">
                    Contacts
                </a>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ $company->contacts_count ?? $company->contacts->count() }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <a href="{{ route('leads.index', ['company_id' => $company->id]) }}"
                    class="text-sm font-medium text-gray-500 hover:text-blue-600 hover:underline transition">
                    Leads
                </a>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ $company->leads_count ?? $company->leads->count() }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <a href="{{ route('deals.index', ['company_id' => $company->id]) }}"
                    class="text-sm font-medium text-gray-500 hover:text-blue-600 hover:underline transition">
                    Deals
                </a>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ $company->deals_count ?? $company->deals->count() }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <a href="{{ route('tasks.index', ['taskable_type' => get_class($company), 'taskable_id' => $company->id]) }}"
                    class="text-sm font-medium text-gray-500 hover:text-blue-600 hover:underline transition">
                    Tasks
                </a>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ $company->tasks_count ?? $company->tasks->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl mb-8 shadow-sm">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900">Company Information</h2>
            </div>

            <div class="divide-y divide-gray-100">
                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Name</span>
                    <span class="text-sm font-medium text-gray-900">{{ $company->name }}</span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Email</span>
                    @if ($company->email)
                        <a href="mailto:{{ $company->email }}" class="text-sm text-gray-900 hover:underline">
                            {{ $company->email }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400">—</span>
                    @endif
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Phone</span>
                    <span class="text-sm text-gray-900">{{ $company->phone ?? '—' }}</span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Website</span>
                    @if ($company->website)
                        <a href="{{ Str::startsWith($company->website, ['http://', 'https://']) ? $company->website : 'https://' . $company->website }}"
                            target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 hover:underline">
                            {{ $company->website }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400">—</span>
                    @endif
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Address</span>
                    <span class="text-sm text-gray-900 text-right">{{ $company->address ?? '—' }}</span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Created</span>
                    <span class="text-sm text-gray-900">{{ $company->created_at->format('d.m.Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl mb-8 shadow-sm">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Contacts</h2>
                @can('create', App\Models\Contact::class)
                    <a href="{{ route('contacts.create', ['company_id' => $company->id]) }}"
                        class="text-sm font-medium text-gray-700 hover:text-gray-900">
                        + Add contact
                    </a>
                @endcan
            </div>

            @if ($company->contacts->isEmpty())
                <div class="px-6 py-8 text-center text-sm text-gray-400">
                    No contacts linked to this company yet.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($company->contacts as $contact)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div>
                                <a href="{{ route('contacts.show', $contact) }}"
                                    class="text-sm font-medium text-gray-900 hover:underline">
                                    {{ $contact->first_name }} {{ $contact->last_name }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $contact->position ?? 'No position' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-gray-600 block">{{ $contact->email ?? '—' }}</span>
                                <span class="text-xs text-gray-400 block">{{ $contact->phone ?? '' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-xl mb-8 shadow-sm">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Deals</h2>
                @can('create', App\Models\Deal::class)
                    <a href="{{ route('deals.create', ['company_id' => $company->id]) }}"
                        class="text-sm font-medium text-gray-700 hover:text-gray-900">
                        + Create deal
                    </a>
                @endcan
            </div>

            @if ($company->deals->isEmpty())
                <div class="px-6 py-8 text-center text-sm text-gray-400">
                    No deals linked to this company yet.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($company->deals as $deal)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div>
                                <a href="{{ route('deals.show', $deal) }}"
                                    class="text-sm font-medium text-gray-900 hover:underline">
                                    {{ $deal->title }}
                                </a>
                                <p class="text-xs text-gray-500">
                                    Amount: <span
                                        class="font-medium text-gray-700">${{ number_format($deal->amount, 2) }}</span>
                                </p>
                            </div>
                            <div>
                                @php
                                    $dealStatus = $deal->status->value ?? $deal->status;
                                    $dealBadgeClass = match (strtolower($dealStatus)) {
                                        'won', 'closed_won' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'lost', 'closed_lost' => 'bg-red-50 text-red-700 border-red-200',
                                        'in_progress', 'negotiation' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span
                                    class="px-2.5 py-1 text-xs font-medium rounded-full border {{ $dealBadgeClass }} capitalize">
                                    {{ str_replace('_', ' ', $dealStatus ?? '—') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-xl mb-8 shadow-sm">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Tasks</h2>
                @can('create', App\Models\Task::class)
                    <a href="{{ route('tasks.create', ['taskable_type' => get_class($company), 'taskable_id' => $company->id]) }}"
                        class="text-sm font-medium text-gray-700 hover:text-gray-900">
                        + Add task
                    </a>
                @endcan
            </div>

            @if ($company->tasks->isEmpty())
                <div class="px-6 py-8 text-center text-sm text-gray-400">
                    No tasks linked to this company yet.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($company->tasks as $task)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div>
                                <a href="{{ route('tasks.show', $task) }}"
                                    class="text-sm font-medium text-gray-900 hover:underline">
                                    {{ $task->title }}
                                </a>
                                <p class="text-xs text-gray-500">
                                    @if ($task->due_date)
                                        @php
                                            $dueDate =
                                                $task->due_date instanceof \Carbon\Carbon
                                                    ? $task->due_date
                                                    : \Carbon\Carbon::parse($task->due_date);
                                            $isOverdue =
                                                $dueDate->isPast() &&
                                                ($task->status->value ?? $task->status) !== 'completed';
                                        @endphp
                                        <span class="{{ $isOverdue ? 'text-red-600 font-medium' : '' }}">
                                            Due: {{ $dueDate->format('d.m.Y') }}
                                        </span>
                                    @else
                                        No due date
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                @php
                                    $statusValue = $task->status->value ?? $task->status;
                                    $statusClass = match (strtolower($statusValue)) {
                                        'completed', 'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'in_progress', 'doing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'pending', 'todo' => 'bg-gray-100 text-gray-700 border-gray-200',
                                        default => 'bg-gray-100 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                                    {{ $statusValue ? ucfirst(str_replace('_', ' ', $statusValue)) : '—' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <x-notes-section :noteable="$company" :notes="$company->notes" />
        </div>
    </div>
</x-app-layout>
