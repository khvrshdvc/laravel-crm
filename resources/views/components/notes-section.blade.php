@props(['noteable', 'notes' => collect()])

<div class="bg-white border border-gray-200 rounded-xl mb-8">
    <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
        <h2 class="font-semibold text-gray-900">
            Notes ({{ $notes->count() }})
        </h2>
    </div>

    <div class="p-6">
        {{-- 1. Yangi izoh qo'shish formasi --}}
        <form action="{{ route('notes.store') }}" method="POST" class="mb-6">
            @csrf
            <input type="hidden" name="noteable_type" value="{{ $noteable->getMorphClass() }}">
            <input type="hidden" name="noteable_id" value="{{ $noteable->id }}">

            <div>
                <textarea name="content" rows="3" required
                    class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-3 border"
                    placeholder="Write a note..."></textarea>
                @error('content')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-3 flex justify-end">
                <button type="submit"
                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Add Note
                </button>
            </div>
        </form>

        {{-- 2. Izohlar ro'yxati --}}
        @if ($notes->isEmpty())
            <div class="py-6 text-center text-sm text-gray-400 border-t border-gray-100">
                No notes created yet.
            </div>
        @else
            <div class="divide-y divide-gray-100 border-t border-gray-100">
                @foreach ($notes as $note)
                    <div x-data="{ editing: false }" class="py-4">

                        {{-- Oddiy ko'rinish --}}
                        <div x-show="!editing" class="flex items-start justify-between gap-4">
                            <div class="space-y-1 w-full">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-gray-900">
                                        {{ $note->user->id ?? 'User' }}
                                    </span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-400">
                                        {{ $note->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $note->content }}</p>
                            </div>

                            {{-- Edit va Delete tugmalari --}}
                            <div class="flex items-center gap-3 shrink-0">
                                <button type="button" @click="editing = true"
                                    class="text-xs font-medium text-gray-500 hover:text-gray-900">
                                    Edit
                                </button>

                                <form action="{{ route('notes.destroy', $note) }}" method="POST"
                                    onsubmit="return confirm('Delete this note?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Tahrirlash formasi (Alpine.js orqali Edit bosilganda chiqadi) --}}
                        <div x-show="editing" x-cloak class="mt-2">
                            <form action="{{ route('notes.update', $note) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <textarea name="content" rows="2" required
                                    class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-3 border">{{ $note->content }}</textarea>

                                <div class="mt-2 flex justify-end gap-2">
                                    <button type="button" @click="editing = false"
                                        class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-gray-900 text-white text-xs font-medium rounded-lg hover:bg-gray-800 transition">
                                        Update Note
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
