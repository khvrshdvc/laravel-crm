<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-8">

        {{-- Back --}}
        <div class="mb-8">
            <a href="{{ route('contacts.index') }}" class="text-sm text-gray-500 hover:text-gray-900">
                ← Back to contacts
            </a>
        </div>

        {{-- Success message --}}
        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex items-start justify-between mb-8">

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
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

            <a href="{{ route('contacts.edit', $contact) }}"
                class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                Edit contact
            </a>

        </div>

        {{-- Summary cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm text-gray-500">
                    Company
                </p>

                <p class="mt-2 text-xl font-semibold text-gray-900">
                    {{ $contact->company?->name ?? '—' }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm text-gray-500">
                    Position
                </p>

                <p class="mt-2 text-xl font-semibold text-gray-900">
                    {{ $contact->position ?? '—' }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm text-gray-500">
                    Created
                </p>

                <p class="mt-2 text-xl font-semibold text-gray-900">
                    {{ $contact->created_at->format('d.m.Y') }}
                </p>
            </div>

        </div>

        {{-- Contact information --}}
        <div class="bg-white border border-gray-200 rounded-xl mb-8">

            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900">
                    Contact information
                </h2>
            </div>

            <div class="divide-y divide-gray-100">

                {{-- First name --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        First name
                    </span>

                    <span class="text-sm font-medium text-gray-900">
                        {{ $contact->first_name }}
                    </span>
                </div>

                {{-- Last name --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Last name
                    </span>

                    <span class="text-sm font-medium text-gray-900">
                        {{ $contact->last_name }}
                    </span>
                </div>

                {{-- Company --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Company
                    </span>

                    @if ($contact->company)
                        <a href="{{ route('companies.show', $contact->company) }}"
                            class="text-sm font-medium text-gray-900 hover:underline">
                            {{ $contact->company->name }}
                        </a>
                    @else
                        <span class="text-sm text-gray-500">
                            —
                        </span>
                    @endif
                </div>

                {{-- Position --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Position
                    </span>

                    <span class="text-sm text-gray-900">
                        {{ $contact->position ?? '—' }}
                    </span>
                </div>

                {{-- Email --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Email
                    </span>

                    @if ($contact->email)
                        <a href="mailto:{{ $contact->email }}" class="text-sm text-gray-900 hover:underline">
                            {{ $contact->email }}
                        </a>
                    @else
                        <span class="text-sm text-gray-500">
                            —
                        </span>
                    @endif
                </div>

                {{-- Phone --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Phone
                    </span>

                    @if ($contact->phone)
                        <a href="tel:{{ $contact->phone }}" class="text-sm text-gray-900 hover:underline">
                            {{ $contact->phone }}
                        </a>
                    @else
                        <span class="text-sm text-gray-500">
                            —
                        </span>
                    @endif
                </div>

                {{-- Created --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Created
                    </span>

                    <span class="text-sm text-gray-900">
                        {{ $contact->created_at->format('d.m.Y') }}
                    </span>
                </div>

            </div>
        </div>

        <div>
            <x-notes-section :noteable="$contact" :notes="$contact->notes" />
        </div>

    </div>
</x-app-layout>
