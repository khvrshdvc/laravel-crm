<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Leads</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Track and manage potential business opportunities.
                </p>
            </div>

            @can('create', App\Models\Lead::class)
                <a href="{{ route('leads.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    + Add lead
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('leads.index') }}" class="mb-6">
            <div class="flex flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name, email or source..."
                    class="flex-1 min-w-[200px] rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-2.5 border">

                <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-2.5 border">
                    <option value="">All statuses</option>
                    <option value="new" @selected(request('status') === 'new')>New</option>
                    <option value="contacted" @selected(request('status') === 'contacted')>Contacted</option>
                    <option value="qualified" @selected(request('status') === 'qualified')>Qualified</option>
                    <option value="lost" @selected(request('status') === 'lost')>Lost</option>
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
                @if (request('search') || request('status') || request('assigned_to'))
                    <a href="{{ route('leads.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-medium text-gray-500">Lead Name</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Status</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Company</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Assigned To</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Source</th>
                            <th class="px-6 py-4 font-medium text-gray-500 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($leads as $lead)
                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4">
                                    <a href="{{ route('leads.show', $lead) }}"
                                        class="font-medium text-gray-900 hover:underline">
                                        {{ $lead->name }}
                                    </a>
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $statusValue = $lead->status->value ?? $lead->status;
                                        $badgeClasses = match ($statusValue) {
                                            'new' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'contacted' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                            'qualified' => 'bg-green-50 text-green-700 border-green-200',
                                            'lost' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $badgeClasses }}">
                                        {{ ucfirst($statusValue) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    @if ($lead->company)
                                        <a href="{{ route('companies.show', $lead->company) }}"
                                            class="hover:underline text-gray-700 font-medium">
                                            {{ $lead->company->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $lead->assignedTo->name ?? 'Unassigned' }}
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $lead->source ?? '—' }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-3">
                                        @can('update', $lead)
                                            <a href="{{ route('leads.edit', $lead) }}"
                                                class="text-gray-600 hover:text-gray-900">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete', $lead)
                                            <form action="{{ route('leads.destroy', $lead) }}" method="POST"
                                                onsubmit="return confirm('Delete this lead?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    @if (request('search') || request('status') || request('assigned_to'))
                                        <p class="text-gray-500">No leads match your filters.</p>
                                    @else
                                        <p class="text-gray-500">No leads found.</p>

                                        @can('create', App\Models\Lead::class)
                                            <a href="{{ route('leads.create') }}"
                                                class="inline-block mt-3 text-sm font-medium text-gray-900 hover:underline">
                                                Add your first lead
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
            {{ $leads->links() }}
        </div>
    </div>
</x-app-layout>
