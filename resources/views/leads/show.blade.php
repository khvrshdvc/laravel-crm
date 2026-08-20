<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-8">

        {{-- Back --}}
        <div class="mb-8">
            <a href="{{ route('leads.index') }}" class="text-sm text-gray-500 hover:text-gray-900">
                ← Back to leads
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
                        {{ strtoupper(substr($lead->name, 0, 1)) }}
                    </span>
                </div>

                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ $lead->name }}
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Source: {{ $lead->source ?? 'Not specified' }}
                    </p>
                </div>
            </div>

            <a href="{{ route('leads.edit', $lead) }}"
                class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                Edit lead
            </a>

        </div>

        {{-- Summary cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm text-gray-500">
                    Status
                </p>

                <div class="mt-2">
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium text-gray-600 tracking-wide uppercase">
                            {{ $lead->status->value ?? $lead->status }}
                        </span>
                    </td>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm text-gray-500">
                    Company
                </p>

                <p class="mt-2 text-xl font-semibold text-gray-900">
                    {{ $lead->company?->name ?? '—' }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <p class="text-sm text-gray-500">
                    Created
                </p>

                <p class="mt-2 text-xl font-semibold text-gray-900">
                    {{ $lead->created_at->format('d.m.Y') }}
                </p>
            </div>

        </div>

        {{-- Lead information --}}
        <div class="bg-white border border-gray-200 rounded-xl">

            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900">
                    Lead information
                </h2>
            </div>

            <div class="divide-y divide-gray-100">

                {{-- Lead Name --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Lead name
                    </span>

                    <span class="text-sm font-medium text-gray-900">
                        {{ $lead->name }}
                    </span>
                </div>

                {{-- Status --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Status
                    </span>

                    <td class="px-6 py-4">
                        <span class="text-xs font-medium text-gray-600 tracking-wide uppercase">
                            {{ $lead->status->value ?? $lead->status }}
                        </span>
                    </td>
                </div>

                {{-- Company --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Company
                    </span>

                    @if ($lead->company)
                        <a href="{{ route('companies.show', $lead->company) }}"
                            class="text-sm font-medium text-gray-900 hover:underline">
                            {{ $lead->company->name }}
                        </a>
                    @else
                        <span class="text-sm text-gray-500">
                            —
                        </span>
                    @endif
                </div>

                {{-- Contact Person --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Contact person
                    </span>

                    @if ($lead->contact)
                        <a href="{{ route('contacts.show', $lead->contact) }}"
                            class="text-sm font-medium text-gray-900 hover:underline">
                            {{ $lead->contact->first_name }} {{ $lead->contact->last_name }}
                        </a>
                    @else
                        <span class="text-sm text-gray-500">
                            —
                        </span>
                    @endif
                </div>

                {{-- Email --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Email
                    </span>

                    @if ($lead->email)
                        <a href="mailto:{{ $lead->email }}" class="text-sm text-gray-900 hover:underline">
                            {{ $lead->email }}
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

                    @if ($lead->phone)
                        <a href="tel:{{ $lead->phone }}" class="text-sm text-gray-900 hover:underline">
                            {{ $lead->phone }}
                        </a>
                    @else
                        <span class="text-sm text-gray-500">
                            —
                        </span>
                    @endif
                </div>

                {{-- Source --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Source
                    </span>

                    <span class="text-sm text-gray-900">
                        {{ $lead->source ?? '—' }}
                    </span>
                </div>

                {{-- Assigned Agent --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Assigned agent
                    </span>

                    <span class="text-sm text-gray-900">
                        {{ $lead->assignedTo?->name ?? '—' }}
                    </span>
                </div>

                {{-- Created By --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Created by
                    </span>

                    <span class="text-sm text-gray-900">
                        {{ $lead->createdBy?->name ?? '—' }}
                    </span>
                </div>

                {{-- Created Date --}}
                <div class="px-6 py-5 flex justify-between gap-6">
                    <span class="text-sm text-gray-500">
                        Created
                    </span>

                    <span class="text-sm text-gray-900">
                        {{ $lead->created_at->format('d.m.Y H:i') }}
                    </span>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
