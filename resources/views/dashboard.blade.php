<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Stat cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-400">Companies</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['companies'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-400">Contacts</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['contacts'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-400">Leads</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['leads'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-400">Active Deals</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_deals'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-400">Won Deals</p>
                    <p class="text-2xl font-semibold text-green-600">{{ $stats['won_deals'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs text-gray-400">Lost Deals</p>
                    <p class="text-2xl font-semibold text-red-500">{{ $stats['lost_deals'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 col-span-2">
                    <p class="text-xs text-gray-400">Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['revenue'], 2) }}</p>
                </div>
            </div>

            {{-- Recent Leads & Today's Tasks --}}
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Recent Leads</h3>
                    @forelse ($recentLeads as $lead)
                        <div class="py-2 border-b border-gray-100 text-sm flex justify-between">
                            <span class="font-medium text-gray-800">{{ $lead->name }}</span>
                            <span class="text-gray-400">{{ $lead->company->name ?? 'No company' }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No leads yet.</p>
                    @endforelse
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Today's Tasks</h3>
                    @forelse ($todayTasks as $task)
                        <div class="py-2 border-b border-gray-100 text-sm flex justify-between">
                            <span class="font-medium text-gray-800">{{ $task->title }}</span>
                            <span class="text-gray-400">{{ $task->assignedTo->name ?? 'Unassigned' }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No tasks for today.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
