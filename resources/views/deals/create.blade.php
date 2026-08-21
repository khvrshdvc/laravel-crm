<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Create Deal
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-6">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <form method="POST" action="{{ route('deals.store') }}">
                    @csrf

                    {{-- Title --}}
                    <div class="mb-5">
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            Title
                        </label>

                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            placeholder="e.g. ERP Software Purchase"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900">

                        @error('title')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Amount & Status --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">
                                Amount ($)
                            </label>

                            <input type="number" step="0.01" name="amount" id="amount"
                                value="{{ old('amount') }}" placeholder="0.00"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900">

                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">
                                Status
                            </label>

                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900 bg-white">
                                @foreach (App\Enums\DealStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('status') == $status->value)>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>

                            @error('status')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Company & Contact --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label for="company_id" class="block text-sm font-medium text-gray-700">
                                Company
                            </label>

                            <select name="company_id" id="company_id"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900 bg-white">
                                <option value="">Select company</option>
                                @foreach ($companies ?? [] as $company)
                                    <option value="{{ $company->id }}" @selected(old('company_id', $selectedCompanyId ?? null) == $company->id)>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('company_id')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_id" class="block text-sm font-medium text-gray-700">
                                Contact Person
                            </label>

                            <select name="contact_id" id="contact_id"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900 bg-white">
                                <option value="">Select contact</option>
                                @foreach ($contacts ?? [] as $contact)
                                    <option value="{{ $contact->id }}" @selected(old('contact_id', $selectedContactId ?? null) == $contact->id)>
                                        {{ $contact->first_name }} {{ $contact->last_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('contact_id')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Assigned To --}}
                    <div class="mb-6">
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700">
                            Assigned To (Manager)
                        </label>

                        <select name="assigned_to" id="assigned_to"
                            class="mt-1 block w-full rounded-md border-gray-300 focus:border-gray-900 focus:ring-gray-900 bg-white">
                            <option value="">Select user</option>

                            @foreach ($users ?? [] as $user)
                                <option value="{{ $user->id }}" @selected(old('assigned_to', auth()->id()) == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('assigned_to')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">

                        <a href="{{ route('deals.index') }}"
                            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>

                        <button type="submit"
                            class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition">
                            Create Deal
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
