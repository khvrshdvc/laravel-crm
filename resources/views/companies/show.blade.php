<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-8">

        <div class="mb-8">
            <a href="{{ route('companies.index') }}" class="text-sm text-gray-500 hover:text-gray-900">
                ← Back to companies
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-start justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                    <span class="text-lg font-semibold text-gray-700">
                        {{ strtoupper(substr($company->name, 0, 1)) }}
                    </span>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ $company->name }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Company details
                    </p>
                </div>
            </div>

            <a href="{{ route('companies.edit', $company) }}"
                class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                Edit company
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm text-gray-500">Contacts</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ $company->contacts_count ?? $company->contacts->count() }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm text-gray-500">Leads</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ $company->leads_count ?? $company->leads->count() }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm text-gray-500">Deals</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">
                    {{ $company->deals_count ?? $company->deals->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900">Company information</h2>
            </div>

            <div class="divide-y divide-gray-100">
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Name</span>
                    <span class="text-sm font-medium text-gray-900">{{ $company->name }}</span>
                </div>

                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Email</span>
                    <span class="text-sm text-gray-900">{{ $company->email ?? '—' }}</span>
                </div>

                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Phone</span>
                    <span class="text-sm text-gray-900">{{ $company->phone ?? '—' }}</span>
                </div>

                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Website</span>
                    @if ($company->website)
                        <a href="{{ $company->website }}" target="_blank"
                            class="text-sm text-gray-900 hover:underline">
                            {{ $company->website }}
                        </a>
                    @else
                        <span class="text-sm text-gray-500">—</span>
                    @endif
                </div>

                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Address</span>
                    <span class="text-sm text-gray-900 text-right">{{ $company->address ?? '—' }}</span>
                </div>

                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Created</span>
                    <span class="text-sm text-gray-900">{{ $company->created_at->format('d.m.Y') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl mt-6">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Contacts</h2>
                <a href="{{ route('contacts.create') }}" class="text-sm text-gray-500 hover:text-gray-900">
                    + Add contact
                </a>
            </div>

            @if ($company->contacts->isEmpty())
                <div class="px-6 py-8 text-center text-sm text-gray-400">
                    No contacts yet.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($company->contacts as $contact)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div>
                                <a href="{{ route('contacts.show', $contact) }}"
                                    class="text-sm font-medium text-gray-900 hover:underline">
                                    {{ $contact->first_name }} {{ $contact->last_name }}
                                </a>
                                <p class="text-xs text-gray-400">{{ $contact->position ?? '—' }}</p>
                            </div>
                            <span class="text-sm text-gray-500">{{ $contact->email ?? '—' }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
