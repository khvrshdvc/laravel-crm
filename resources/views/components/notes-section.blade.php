@props(['noteable', 'notes' => collect()])

<div class="bg-white border border-gray-200 rounded-xl mb-8 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
            <span>Notes</span>
            <span class="px-2 py-0.5 text-xs bg-gray-200 text-gray-700 rounded-full font-medium">
                {{ $notes->count() }}
            </span>
        </h2>
    </div>

    <div class="p-6">
        <!-- Add Note Form -->
        <form action="{{ route('notes.store') }}" method="POST" class="mb-6">
            @csrf
            <input type="hidden" name="noteable_type" value="{{ $noteable->getMorphClass() }}">
            <input type="hidden" name="noteable_id" value="{{ $noteable->id }}">

            <div>
                <textarea name="content" rows="3" required
                    class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-3 border placeholder-gray-400 focus:outline-none transition"
                    placeholder="Write a note or update regarding this {{ strtolower(class_basename($noteable)) }}..."></textarea>

                @error('content')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-3 flex justify-end">
                <button type="submit"
                    class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 transition">
                    Add Note
                </button>
            </div>
        </form>

        <!-- Notes List -->
        @if ($notes->isEmpty())
            <div class="py-8 text-center text-sm text-gray-400 border-t border-gray-100">
                No notes created yet.
            </div>
        @else
            <div class="divide-y divide-gray-100 border-t border-gray-100">
                @foreach ($notes as $note)
                    <div x-data="{ editing: false }" class="py-4">

                        <!-- Read Mode -->
                        <div x-show="!editing" class="flex items-start justify-between gap-4">
                            <div class="space-y-1.5 w-full">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-semibold text-gray-900">
                                        {{ $note->user?->name ?? 'System User' }}
                                    </span>
                                    <span class="text-xs text-gray-300">•</span>
                                    <span class="text-xs text-gray-400"
                                        title="{{ $note->created_at->format('Y-m-d H:i') }}">
                                        {{ $note->created_at->diffForHumans() }}
                                    </span>
                                    @if ($note->updated_at->gt($note->created_at))
                                        <span class="text-xs text-gray-400 italic">(edited)</span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                                    {{ $note->content }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-3 shrink-0 pt-0.5">
                                @can('update', $note)
                                    <button type="button" @click="editing = true"
                                        class="text-xs font-medium text-gray-500 hover:text-gray-900 transition">
                                        Edit
                                    </button>
                                @endcan

                                @can('delete', $note)
                                    <form action="{{ route('notes.destroy', $note) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this note?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs font-medium text-red-500 hover:text-red-700 transition">
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <!-- Edit Mode -->
                        @can('update', $note)
                            <div x-show="editing" x-cloak class="mt-2">
                                <form action="{{ route('notes.update', $note) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <textarea name="content" rows="2" required
                                        class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm p-3 border focus:outline-none transition">{{ $note->content }}</textarea>

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
                        @endcan

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
