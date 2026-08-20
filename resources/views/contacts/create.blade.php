<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-8">

        <div class="mb-8">
            <a href="{{ route('contacts.index') }}" class="text-sm text-gray-500 hover:text-gray-900">
                ← Back to contacts
            </a>

            <h1 class="mt-4 text-2xl font-semibold text-gray-900">
                Add contact
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Create a new contact in your CRM.
            </p>
        </div>

        <form action="{{ route('contacts.store') }}" method="POST" class="bg-white border border-gray-200 rounded-xl p-6"
            novalidate>
            @csrf

            <div class="space-y-6">

                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">
                        First name
                    </label>

                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900">

                    @error('first_name')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">
                        Last name
                    </label>

                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900">

                    @error('last_name')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email
                    </label>

                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900">

                    @error('email')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Phone
                    </label>

                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900">

                    @error('phone')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700">
                        Company
                    </label>

                    <select id="company_id" name="company_id"
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 bg-white">
                        <option value="">Select company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}"
                                {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('company_id')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">

                <a href="{{ route('contacts.index') }}"
                    class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900">
                    Cancel
                </a>

                <button type="submit"
                    class="px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                    Create contact
                </button>

            </div>

        </form>

    </div>
</x-app-layout>
