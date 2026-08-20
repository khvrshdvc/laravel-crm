<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-8">

        <div class="mb-8">
            <a href="{{ route('leads.index') }}" class="text-sm text-gray-500 hover:text-gray-900">
                ← Back to leads
            </a>

            <h1 class="mt-4 text-2xl font-semibold text-gray-900">
                Add lead
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Create a new lead in your CRM.
            </p>
        </div>

        <form action="{{ route('leads.store') }}" method="POST" class="bg-white border border-gray-200 rounded-xl p-6"
            novalidate>
            @csrf

            <div class="space-y-6">

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Title
                    </label>

                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900">

                    @error('title')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">
                        Status
                    </label>

                    <select id="status" name="status" required
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 bg-white">
                        <option value="">Select status</option>
                        @foreach (App\Enums\LeadStatus::cases() as $status)
                            <option value="{{ $status->value }}"
                                {{ old('status') == $status->value ? 'selected' : '' }}>
                                {{ ucfirst($status->value) }}
                            </option>
                        @endforeach
                    </select>

                    @error('status')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_id" class="block text-sm font-medium text-gray-700">
                        Contact
                    </label>

                    <select id="contact_id" name="contact_id"
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 bg-white">
                        <option value="">Select contact</option>
                        @foreach ($contacts as $contact)
                            <option value="{{ $contact->id }}"
                                {{ old('contact_id') == $contact->id ? 'selected' : '' }}>
                                {{ $contact->first_name }} {{ $contact->last_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('contact_id')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">
                        Notes
                    </label>

                    <textarea id="notes" name="notes" rows="3"
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900">{{ old('notes') }}</textarea>

                    @error('notes')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">

                <a href="{{ route('leads.index') }}"
                    class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900">
                    Cancel
                </a>

                <button type="submit"
                    class="px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Create lead
                </button>

            </div>

        </form>

    </div>
</x-app-layout>
