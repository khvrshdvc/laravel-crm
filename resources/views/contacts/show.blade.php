<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-8">

        <div class="mb-8">
            <a href="{{ route('contacts.index') }}"
                class="text-sm text-gray-500 hover:text-gray-900 transition flex items-center gap-1">
                &larr; Back to contacts
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
                        {{ strtoupper(substr($contact->first_name, 0, 1)) }}
                    </span>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ $contact->first_name }} {{ $contact->last_name }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $contact->position ?? 'Contact details' }}
                    </p>
                </div>
            </div>

            @can('update', $contact)
                <a href="{{ route('contacts.edit', $contact) }}"
                    class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Edit contact
                </a>
            @endcan
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Company</p>
                <p class="mt-2 text-xl font-semibold text-gray-900">
                    @if ($contact->company)
                        <a href="{{ route('companies.show', $contact->company) }}"
                            class="hover:underline hover:text-blue-600 transition">
                            {{ $contact->company->name }}
                        </a>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Position</p>
                <p class="mt-2 text-xl font-semibold text-gray-900">
                    {{ $contact->position ?? '—' }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <p class="text-sm font-medium text-gray-500">Created</p>
                <p class="mt-2 text-xl font-semibold text-gray-900">
                    {{ $contact->created_at->format('d.m.Y') }}
                </p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl mb-8 shadow-sm">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900">Contact information</h2>
            </div>

            <div class="divide-y divide-gray-100">
                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">First name</span>
                    <span class="text-sm font-medium text-gray-900">{{ $contact->first_name }}</span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Last name</span>
                    <span class="text-sm font-medium text-gray-900">{{ $contact->last_name }}</span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Company</span>
                    @if ($contact->company)
                        <a href="{{ route('companies.show', $contact->company) }}"
                            class="text-sm font-medium text-gray-900 hover:underline">
                            {{ $contact->company->name }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400">—</span>
                    @endif
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Position</span>
                    <span class="text-sm text-gray-900">{{ $contact->position ?? '—' }}</span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Email</span>
                    @if ($contact->email)
                        <a href="mailto:{{ $contact->email }}" class="text-sm text-blue-600 hover:underline">
                            {{ $contact->email }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400">—</span>
                    @endif
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Phone</span>
                    @if ($contact->phone)
                        <a href="tel:{{ $contact->phone }}" class="text-sm text-gray-900 hover:underline">
                            {{ $contact->phone }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400">—</span>
                    @endif
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Created</span>
                    <span class="text-sm text-gray-900">{{ $contact->created_at->format('d.m.Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div>
            <x-notes-section :noteable="$contact" :notes="$contact->notes" />
        </div>
    </div>
</x-app-layout>
