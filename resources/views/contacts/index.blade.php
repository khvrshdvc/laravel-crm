<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                Contacts
            </h2>
            <a href="{{ route('contacts.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition">
                + New Contact
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-md bg-green-50 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                @if ($contacts->isEmpty())
                    <div class="p-10 text-center text-gray-400 text-sm">
                        No contacts yet.
                        <a href="{{ route('contacts.create') }}" class="text-gray-900 underline">Add your first one</a>.
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-400 uppercase tracking-wide">
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Company</th>
                                <th class="px-6 py-3">Position</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($contacts as $contact)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        <a href="{{ route('contacts.show', $contact) }}" class="hover:underline">
                                            {{ $contact->first_name }} {{ $contact->last_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $contact->company->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $contact->position ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $contact->email ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <a href="{{ route('contacts.edit', $contact) }}"
                                            class="text-gray-400 hover:text-gray-900 transition">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="mt-6">
                {{ $contacts->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
