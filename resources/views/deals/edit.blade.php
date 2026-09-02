<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-8">
        <div class="mb-8">
            <a href="{{ route('deals.show', $deal) }}" class="text-sm text-gray-500 hover:text-gray-900 transition">
                ← Back to deal details
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">
                Edit Deal
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Update details for {{ $deal->title }}
            </p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6">
            <form method="POST" action="{{ route('deals.update', $deal) }}">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="title" id="title" value="{{ old('title', $deal->title) }}"
                        required placeholder="e.g. ERP Software Purchase"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">

                    @error('title')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                            Amount ($)
                        </label>

                        <input type="number" step="0.01" name="amount" id="amount"
                            value="{{ old('amount', $deal->amount) }}" placeholder="0.00"
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
                                <option value="{{ $status->value }}" @selected(old('status', $deal->status?->value ?? $deal->status) === $status->value)>
                                    {{ method_exists($status, 'label') ? $status->label() : ucfirst($status->value) }}
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Company
                        </label>

                        <select name="company_id" id="company_id"
                            class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                            <option value="">Select company</option>
                            @foreach ($companies ?? [] as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id', $deal->company_id) == $company->id)>
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
                                <option value="{{ $contact->id }}" @selected(old('contact_id', $deal->contact_id) == $contact->id)>
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

                <div class="mb-8">
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">
                        Assigned To (Manager)
                    </label>

                    <select name="assigned_to" id="assigned_to"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                        <option value="">Select user</option>
                        @foreach ($users ?? [] as $user)
                            <option value="{{ $user->id }}" @selected(old('assigned_to', $deal->assigned_to) == $user->id)>
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

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('deals.show', $deal) }}"
                        class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 transition">
                        Cancel
                    </a>

                    <button type="submit"
                        class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                        Save Changes
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Delete Deal</h3>
                    <p class="text-xs text-gray-500">Once deleted, this action cannot be undone.</p>
                </div>

                <form method="POST" action="{{ route('deals.destroy', $deal) }}"
                    onsubmit="return confirm('Are you sure you want to delete this deal?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="px-3.5 py-2 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                        Delete Deal
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
