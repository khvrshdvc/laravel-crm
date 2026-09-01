<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-8">

        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ route('deals.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition">
                ← Back to deals
            </a>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Deal Header --}}
        <div class="flex items-start justify-between mb-8">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center border border-emerald-100">
                    <span class="text-lg font-semibold text-emerald-600">
                        {{ strtoupper(substr($deal->title, 0, 1)) }}
                    </span>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ $deal->title }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Deal #{{ $deal->id }} • Details & activity history
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('deals.edit', $deal) }}"
                    class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Edit deal
                </a>
            </div>
        </div>

        {{-- Deal Details --}}
        <div class="bg-white border border-gray-200 rounded-xl mb-8">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900">Deal Information</h2>
            </div>

            <div class="divide-y divide-gray-100">
                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Title</span>
                    <span class="text-sm font-medium text-gray-900">{{ $deal->title }}</span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Amount</span>
                    <span class="text-sm font-semibold text-emerald-600">
                        {{ $deal->amount ? '$' . number_format($deal->amount, 2) : '—' }}
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Status</span>
                    @php
                        $statusValue = $deal->status->value ?? $deal->status;
                    @endphp
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 uppercase">
                        {{ $statusValue ?? '—' }}
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Assigned To</span>
                    <span class="text-sm text-gray-900">
                        {{ $deal->assignedUser?->name ?? '—' }}
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Company</span>
                    <span class="text-sm text-gray-900">
                        @if ($deal->company)
                            <a href="{{ route('companies.show', $deal->company) }}"
                                class="hover:underline font-medium text-gray-900">
                                {{ $deal->company->name }}
                            </a>
                        @else
                            —
                        @endif
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Contact</span>
                    <span class="text-sm text-gray-900">
                        @if ($deal->contact)
                            <a href="{{ route('contacts.show', $deal->contact) }}"
                                class="hover:underline font-medium text-gray-900">
                                {{ $deal->contact->first_name }} {{ $deal->contact->last_name }}
                            </a>
                        @else
                            —
                        @endif
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Lead Source</span>
                    <span class="text-sm text-gray-900">
                        @if ($deal->lead)
                            <a href="{{ route('leads.show', $deal->lead) }}"
                                class="hover:underline font-medium text-gray-900">
                                {{ $deal->lead->name }}
                            </a>
                        @else
                            Direct Deal
                        @endif
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Created By</span>
                    <span class="text-sm text-gray-900">
                        {{ $deal->creator?->name ?? '—' }}
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Created At</span>
                    <span class="text-sm text-gray-900">{{ $deal->created_at?->format('d.m.Y H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Notes Section --}}
        <div>
            <x-notes-section :noteable="$deal" :notes="$deal->notes" />
        </div>

    </div>
</x-app-layout>
