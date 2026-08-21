<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $deal->title }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Deal #{{ $deal->id }}
                </p>
            </div>

            <a href="{{ route('deals.index') }}" class="text-sm text-gray-500 hover:text-gray-900">
                ← Back to deals
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-6">

            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg">

                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Deal Information
                    </h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <p class="text-sm text-gray-500">
                            Title
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $deal->title }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Amount
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $deal->amount ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Status
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $deal->status->value }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Assigned To
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $deal->assignedUser?->name ?? '—' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Company
                        </p>

                        @if ($deal->company)
                            <a href="{{ route('companies.show', $deal->company) }}"
                                class="mt-1 inline-block font-medium text-gray-900 hover:underline">
                                {{ $deal->company->name }}
                            </a>
                        @else
                            <p class="mt-1 text-gray-500">—</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Contact
                        </p>

                        @if ($deal->contact)
                            <a href="{{ route('contacts.show', $deal->contact) }}"
                                class="mt-1 inline-block font-medium text-gray-900 hover:underline">
                                {{ $deal->contact->first_name}} {{ $deal->contact->last_name}}
                            </a>
                        @else
                            <p class="mt-1 text-gray-500">—</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Lead
                        </p>

                        @if ($deal->lead)
                            <a href="{{ route('leads.show', $deal->lead) }}"
                                class="mt-1 inline-block font-medium text-gray-900 hover:underline">
                                {{ $deal->lead->name }}
                            </a>
                        @else
                            <p class="mt-1 text-gray-500">
                                Direct Deal
                            </p>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">
                            Created By
                        </p>

                        <p class="mt-1 font-medium text-gray-900">
                            {{ $deal->creator?->name ?? '—' }}
                        </p>
                    </div>

                </div>

                <div class="px-6 py-4 border-t flex items-center justify-between">

                    <a href="{{ route('deals.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        ← Back
                    </a>

                    <a href="{{ route('deals.edit', $deal) }}"
                        class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800">
                        Edit Deal
                    </a>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
