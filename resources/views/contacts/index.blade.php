<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Contacts</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your individual contacts and people.
                </p>
            </div>

            @can('create', App\Models\Contact::class)
                <a href="{{ route('contacts.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    + Add contact
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('contacts.index') }}" class="mb-6">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name, email or phone..."
                    class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-2.5 border">
                
                <button type="submit"
                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Search
                </button>

                @if (request('search'))
                    <a href="{{ route('contacts.index') }}"
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
                            <th class="px-6 py-4 font-medium text-gray-500">Name</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Company</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Email</th>
                            <th class="px-6 py-4 font-medium text-gray-500">Phone</th>
                            <th class="px-6 py-4 font-medium text-gray-500 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($contacts as $contact)
                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4">
                                    <a href="{{ route('contacts.show', $contact) }}"
                                        class="font-medium text-gray-900 hover:underline">
                                        {{ $contact->first_name }} {{ $contact->last_name }}
                                    </a>
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    @if ($contact->company)
                                        <a href="{{ route('companies.show', $contact->company) }}"
                                            class="hover:underline text-gray-700 font-medium">
                                            {{ $contact->company->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $contact->email ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-500">
                                    {{ $contact->phone ?? '—' }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-3">
                                        @can('update', $contact)
                                            <a href="{{ route('contacts.edit', $contact) }}"
                                                class="text-gray-600 hover:text-gray-900">
                                                Edit
                                            </a>
                                        @endcan

                                        @can('delete', $contact)
                                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST"
                                                onsubmit="return confirm('Delete this contact?')">
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
                                <td colspan="5" class="px-6 py-12 text-center">
                                    @if (request('search'))
                                        <p class="text-gray-500">No contacts match "{{ request('search') }}".</p>
                                    @else
                                        <p class="text-gray-500">No contacts found.</p>

                                        @can('create', App\Models\Contact::class)
                                            <a href="{{ route('contacts.create') }}"
                                                class="inline-block mt-3 text-sm font-medium text-gray-900 hover:underline">
                                                Add your first contact
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
            {{ $contacts->links() }}
        </div>
    </div>
</x-app-layout>