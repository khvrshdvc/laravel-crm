<x-app-layout>

    <div class="max-w-3xl mx-auto px-6 py-8">

        {{-- Back --}}
        <div class="mb-8">
            <a
                href="{{ route('contacts.show', $contact) }}"
                class="text-sm text-gray-500 hover:text-gray-900"
            >
                ← Back to contact
            </a>
        </div>

        {{-- Page header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">
                Edit contact
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Update contact information.
            </p>
        </div>

        {{-- Form --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">

            <form
                method="POST"
                action="{{ route('contacts.update', $contact) }}"
            >
                @csrf
                @method('PUT')

                <div class="space-y-6">

                    {{-- Name --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- First name --}}
                        <div>
                            <label
                                for="first_name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                First name
                            </label>

                            <input
                                type="text"
                                name="first_name"
                                id="first_name"
                                value="{{ old('first_name', $contact->first_name) }}"
                                class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                            >

                            @error('first_name')
                                <p class="mt-1.5 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Last name --}}
                        <div>
                            <label
                                for="last_name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Last name
                            </label>

                            <input
                                type="text"
                                name="last_name"
                                id="last_name"
                                value="{{ old('last_name', $contact->last_name) }}"
                                class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                            >

                            @error('last_name')
                                <p class="mt-1.5 text-sm text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    {{-- Company --}}
                    <div>
                        <label
                            for="company_id"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Company
                        </label>

                        <select
                            name="company_id"
                            id="company_id"
                            class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                        >
                            <option value="">
                                Select a company
                            </option>

                            @foreach ($companies as $company)
                                <option
                                    value="{{ $company->id }}"
                                    @selected(old('company_id', $contact->company_id) == $company->id)
                                >
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('company_id')
                            <p class="mt-1.5 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Position --}}
                    <div>
                        <label
                            for="position"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Position
                        </label>

                        <input
                            type="text"
                            name="position"
                            id="position"
                            value="{{ old('position', $contact->position) }}"
                            placeholder="CEO"
                            class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                        >

                        @error('position')
                            <p class="mt-1.5 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label
                            for="email"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $contact->email) }}"
                            placeholder="john@company.com"
                            class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                        >

                        @error('email')
                            <p class="mt-1.5 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label
                            for="phone"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            value="{{ old('phone', $contact->phone) }}"
                            placeholder="+998 90 000 00 00"
                            class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900"
                        >

                        @error('phone')
                            <p class="mt-1.5 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">

                    <a
                        href="{{ route('contacts.show', $contact) }}"
                        class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition"
                    >
                        Save changes
                    </button>

                </div>

            </form>

        </div>

        {{-- Delete section --}}
        <div class="mt-6 bg-white border border-gray-200 rounded-xl p-6">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-sm font-semibold text-gray-900">
                        Delete contact
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        This action cannot be undone.
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('contacts.destroy', $contact) }}"
                    onsubmit="return confirm('Delete this contact?');"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="px-4 py-2.5 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition"
                    >
                        Delete
                    </button>
                </form>

            </div>

        </div>

    </div>

</x-app-layout>