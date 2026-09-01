<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-8">

        {{-- Back Button --}}
        <div class="mb-8">
            <a href="{{ route('deals.index') }}" class="text-sm text-gray-500 hover:text-gray-900 transition">
                ← Back to deals
            </a>
        </div>

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">
                Create Deal
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Add a new deal opportunity to your pipeline
            </p>
        </div>

        {{-- Form Card --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <form method="POST" action="{{ route('deals.store') }}">
                @csrf

                {{-- Title --}}
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        placeholder="e.g. ERP Software Purchase"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">

                    @error('title')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Amount & Status --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                            Amount ($)
                        </label>

                        <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}"
                            placeholder="0.00"
                            class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">

                        @error('amount')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>

                        <select name="status" id="status" required
                            class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                            @foreach (App\Enums\DealStatus::cases() as $status)
                                <option value="{{ $status->value }}" @selected(old('status') == $status->value)>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>

                        @error('status')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Company & Contact --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Company
                        </label>

                        <select name="company_id" id="company_id"
                            class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                            <option value="">Select company</option>
                            @foreach ($companies ?? [] as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id', $selectedCompanyId ?? null) == $company->id)>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('company_id')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Contact Person
                        </label>

                        <select name="contact_id" id="contact_id"
                            class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                            <option value="">Select contact</option>
                            @foreach ($contacts ?? [] as $contact)
                                <option value="{{ $contact->id }}" @selected(old('contact_id', $selectedContactId ?? null) == $contact->id)>
                                    {{ $contact->first_name }} {{ $contact->last_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('contact_id')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Assigned To --}}
                <div class="mb-8">
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">
                        Assigned To (Manager)
                    </label>

                    <select name="assigned_to" id="assigned_to"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                        <option value="">Select user</option>
                        @foreach ($users ?? [] as $user)
                            <option value="{{ $user->id }}" @selected(old('assigned_to', auth()->id()) == $user->id)>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('assigned_to')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('deals.index') }}"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 transition">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                        Create Deal
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
