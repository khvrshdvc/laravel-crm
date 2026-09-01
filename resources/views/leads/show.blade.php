<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-8">

        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ route('leads.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition">
                ← Back to leads
            </a>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Lead Header --}}
        <div class="flex items-start justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center border border-blue-100">
                    <span class="text-lg font-semibold text-blue-600">
                        {{ strtoupper(substr($lead->name, 0, 1)) }}
                    </span>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ $lead->name }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Lead details & activity history
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                @if (($lead->status->value ?? $lead->status) !== 'converted')
                    <a href="{{ route('leads.convert', $lead) }}"
                        class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                        Convert lead
                    </a>
                @else
                    <span
                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-green-50 text-green-700 border border-green-200">
                        ✓ Converted
                    </span>
                @endif

                <a href="{{ route('leads.edit', $lead) }}"
                    class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Edit lead
                </a>
            </div>
        </div>

        {{-- Lead Details --}}
        <div class="bg-white border border-gray-200 rounded-xl mb-8">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900">Lead Information</h2>
            </div>

            <div class="divide-y divide-gray-100">
                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Name</span>
                    <span class="text-sm font-medium text-gray-900">{{ $lead->name }}</span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Status</span>
                    @php
                        $statusValue = $lead->status->value ?? $lead->status;
                    @endphp
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 uppercase">
                        {{ $statusValue ?? '—' }}
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Email</span>
                    <span class="text-sm text-gray-900">{{ $lead->email ?? '—' }}</span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Phone</span>
                    <span class="text-sm text-gray-900">{{ $lead->phone ?? '—' }}</span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Company</span>
                    <span class="text-sm text-gray-900">
                        {{ $lead->company->name ?? '—' }}
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Assigned To</span>
                    <span class="text-sm text-gray-900">
                        {{ $lead->assignedTo->name ?? ($lead->user->name ?? 'Unassigned') }}
                    </span>
                </div>

                <div class="px-6 py-4 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">Created</span>
                    <span class="text-sm text-gray-900">{{ $lead->created_at->format('d.m.Y H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Notes Section --}}
        <div>
            <x-notes-section :noteable="$lead" :notes="$lead->notes" />
        </div>

    </div>
</x-app-layout>
