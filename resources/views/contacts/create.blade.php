<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- Header --}}
            <div class="mb-8">
                <a href="{{ route('contacts.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-gray-900">
                    <span class="text-lg">←</span>
                    Back to contacts
                </a>

                <div class="mt-5">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                        Add contact
                    </h1>

                    <p class="mt-2 text-sm text-gray-500">
                        Create a new contact and connect them to a company.
                    </p>
                </div>
            </div>

            {{-- Form Card --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                <form method="POST" action="{{ route('contacts.store') }}">
                    @csrf

                    <div class="p-6 sm:p-8">
                        <div class="space-y-6">

                            {{-- Name --}}
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                                {{-- First name --}}
                                <div>
                                    <label for="first_name" class="block text-sm font-semibold text-gray-800">
                                        First name
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input type="text" name="first_name" id="first_name"
                                        value="{{ old('first_name') }}" placeholder="John"
                                        class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400
                                        {{ $errors->has('first_name')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                            : 'border-gray-300 focus:border-gray-900 focus:ring-4 focus:ring-gray-100' }}">

                                    @error('first_name')
                                        <p class="mt-2 text-sm text-red-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Last name --}}
                                <div>
                                    <label for="last_name" class="block text-sm font-semibold text-gray-800">
                                        Last name
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                        placeholder="Smith"
                                        class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400
                                        {{ $errors->has('last_name')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                            : 'border-gray-300 focus:border-gray-900 focus:ring-4 focus:ring-gray-100' }}">

                                    @error('last_name')
                                        <p class="mt-2 text-sm text-red-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                            </div>

                            {{-- Company --}}
                            <div>
                                <label for="company_id" class="block text-sm font-semibold text-gray-800">
                                    Company
                                    <span class="text-red-500">*</span>
                                </label>

                                <select name="company_id" id="company_id"
                                    class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition
                                    {{ $errors->has('company_id')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-gray-300 focus:border-gray-900 focus:ring-4 focus:ring-gray-100' }}">
                                    <option value="">
                                        Select a company
                                    </option>

                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" @selected(old('company_id', request('company_id')) == $company->id)>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('company_id')
                                    <p class="mt-2 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Position --}}
                            <div>
                                <label for="position" class="block text-sm font-semibold text-gray-800">
                                    Position
                                </label>

                                <input type="text" name="position" id="position" value="{{ old('position') }}"
                                    placeholder="CEO"
                                    class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400
                                    {{ $errors->has('position')
                                        ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                        : 'border-gray-300 focus:border-gray-900 focus:ring-4 focus:ring-gray-100' }}">

                                @error('position')
                                    <p class="mt-2 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Contact information --}}
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-800">
                                        Email
                                    </label>

                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        placeholder="john@company.com"
                                        class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400
                                        {{ $errors->has('email')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                            : 'border-gray-300 focus:border-gray-900 focus:ring-4 focus:ring-gray-100' }}">

                                    @error('email')
                                        <p class="mt-2 text-sm text-red-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-800">
                                        Phone
                                        <span class="text-red-500">*</span>
                                    </label>

                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                        placeholder="+998 90 000 00 00"
                                        class="mt-2 block w-full rounded-xl border px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400
                                        {{ $errors->has('phone')
                                            ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100'
                                            : 'border-gray-300 focus:border-gray-900 focus:ring-4 focus:ring-gray-100' }}">

                                    @error('phone')
                                        <p class="mt-2 text-sm text-red-500">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- Footer --}}
                    <div
                        class="flex flex-col-reverse gap-3 border-t border-gray-100 bg-gray-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-end sm:px-8">

                        <a href="{{ route('contacts.index') }}"
                            class="rounded-xl px-5 py-3 text-center text-sm font-semibold text-gray-600 transition hover:bg-gray-200 hover:text-gray-900">
                            Cancel
                        </a>

                        <button type="submit"
                            class="rounded-xl bg-gray-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-gray-300">
                            Save contact
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
