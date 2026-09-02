<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- Header Section --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Deals</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Manage and track your active deals in the pipeline.
                </p>
            </div>

            @can('create', App\Models\Deal::class)
                <a href="{{ route('deals.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    + Add deal
                </a>
            @endcan
        </div>

        {{-- Flash Success Message --}}
        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('deals.index') }}" class="mb-6">
            <div class="flex flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..."
                    class="flex-1 min-w-[200px] rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-2.5 border">

                <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-2.5 border">
                    <option value="">All statuses</option>
                    <option value="new" @selected(request('status') === 'new')>New</option>
                    <option value="contacted" @selected(request('status') === 'contacted')>Contacted</option>
                    <option value="qualified" @selected(request('status') === 'qualified')>Qualified</option>
                    <option value="proposal" @selected(request('status') === 'proposal')>Proposal</option>
                    <option value="negotiation" @selected(request('status') === 'negotiation')>Negotiation</option>
                    <option value="won" @selected(request('status') === 'won')>Won</option>
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
                    <a href="{{ route('deals.index') }}"
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
                            <th class="px-6 py-4 font-medium text-gray-500">Title</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Company</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Contact</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Assigned To</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Amount</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Status</th>
                            <th class="px-6 py-4 font-medium text-gray-500 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($deals as $deal)
                            <tr class="hover:bg-gray-50 transition">

                                {{-- Title --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('deals.show', $deal) }}"
                                        class="font-medium text-gray-900 hover:underline">
                                        {{ $deal->title }}
                                    </a>
                                </td>

                                {{-- Company --}}
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    @if ($deal->company)
                                        <a href="{{ route('companies.show', $deal->company) }}"
                                            class="hover:underline text-gray-700 font-medium">
                                            {{ $deal->company->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Contact --}}
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    @if ($deal->contact)
                                        <a href="{{ route('contacts.show', $deal->contact) }}"
                                            class="hover:underline text-gray-700">
                                            {{ $deal->contact->first_name }} {{ $deal->contact->last_name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Assigned To --}}
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $deal->assignedTo->name ?? 'Unassigned' }}
                                </td>

                                {{-- Amount --}}
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ $deal->amount ? '$' . number_format($deal->amount, 2) : '—' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusValue = $deal->status->value ?? $deal->status;
                                        $badgeClasses = match ($statusValue) {
                                            'new' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'contacted' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'qualified' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'proposal' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'negotiation' => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'won' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'lost' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $badgeClasses }}">
                                        {{ ucfirst($statusValue) }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end items-center gap-3">
                                        @can('update', $deal)
                                            <a href="{{ route('deals.edit', $deal) }}"
                                                class="text-gray-600 hover:text-gray-900 transition">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete', $deal)
                                            <form action="{{ route('deals.destroy', $deal) }}" method="POST"
                                                onsubmit="return confirm('Delete this deal?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="text-red-500 hover:text-red-700 transition">
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
                                    @if (request('search') || request('status') || request('assigned_to'))
                                        <p class="text-gray-500">No deals match your filters.</p>
                                    @else
                                        <p class="text-gray-500">No deals found.</p>

                                        @can('create', App\Models\Deal::class)
                                            <a href="{{ route('deals.create') }}"
                                                class="inline-block mt-3 text-sm font-medium text-gray-900 hover:underline">
                                                Add your first deal
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

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $deals->links() }}
        </div>

    </div>
</x-app-layout>
