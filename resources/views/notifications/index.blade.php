<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bildirishnomalar') }}
            </h2>

            @if (auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-3.5 py-2 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-sm">
                        Hammasini o'qilgan deb belgilash
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="divide-y divide-gray-100">
                        @forelse($notifications as $notification)
                            <a href="{{ route('notifications.show', $notification->id) }}"
                                class="group py-4 px-4 -mx-4 rounded-lg flex items-center justify-between transition-colors duration-150 {{ $notification->read_at ? 'hover:bg-gray-50' : 'bg-indigo-50/50 hover:bg-indigo-50' }}">
                                <div class="flex items-start gap-x-3">
                                    <!-- Unread indicator dot -->
                                    <div class="mt-1.5 flex-shrink-0">
                                        @if (!$notification->read_at)
                                            <span class="inline-block h-2 w-2 rounded-full bg-indigo-600 ring-4 ring-indigo-100"></span>
                                        @else
                                            <span class="inline-block h-2 w-2 rounded-full bg-transparent"></span>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium {{ $notification->read_at ? 'text-gray-600' : 'text-gray-900 font-semibold' }}">
                                            {{ $notification->data['message'] ?? ($notification->data['title'] ?? 'Yangi bildirishnoma') }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $notification->created_at->format('d.m.Y H:i') }}
                                            ({{ $notification->created_at->diffForHumans() }})
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-x-3">
                                    @if (!$notification->read_at)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-indigo-100 text-indigo-700">
                                            Yangi
                                        </span>
                                    @endif

                                    <!-- Arrow Icon -->
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-12 text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-sm">Hech qanday bildirishnoma topilmadi.</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($notifications->hasPages())
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>